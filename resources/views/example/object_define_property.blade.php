@extends('layouts.example')
@php
    $title = 'DOM変更の検知にObject.definePropertyを使ったパターン';
@endphp

@section('title', $title)

@section('script')
<script type="text/javascript">
    $(() => {
        console.info('{{ $title }}');

        // K:都道府県名 V:都道府県コード
        const prefs = @json(array_flip(config('const.Prefs'))),
        // フォームに割り当てるイベント
        attachFormEvents = table => {
            $('[id^=pref], [id^=city]', table).select2({
                theme: 'bootstrap4',
            });

            $('[id^=zip]', table).keyup(e => {
                if ($(e.target).val().length !== 7) {
                    $('[id^=pref], [id^=city]', table).val('').trigger('change');
                }
            });

            $('[id^=search]', table).click(e => {
                let index = $(e.currentTarget).data('index');
                document.querySelector('#zip' + index).dispatchEvent(new KeyboardEvent("keyup"));
            });

            @php
            // Yubinbangoでchangeなどのイベントは取得できないためhidden要素へのget、setアクセサーでselect2の制御を行う
            // Yubinbangoの変更は都道府県→市区町村→町域の順番で呼ばれる模様
            // 都道府県と市区町村の動的なselect2の変更は市区町村のset時に行う
            // 参考:https://teratail.com/questions/94990?sip=n0070000_019
            @endphp
            ($target => {
                let target = $target.get(0), index = $target.data('index');
                Object.defineProperty(target, 'value', {
                    get: () => {
                        return Object.getOwnPropertyDescriptor(HTMLInputElement.prototype,'value').get.call(target)
                    },
                    set: city => {
                        if (city) {
                            let pref_code = prefs[$target.prev('.p-region').val()];
                            $('#pref' + index).val(pref_code).trigger('change');
                            $('#city' + index).empty().append(new Option('選択してください', '', false, false));
                            $.getJSON(`/json_data/${pref_code}.json`, json_cities => {
                                json_cities.forEach(json_city => {
                                    let selected = json_city === city;
                                    $('#city' + index).append(new Option(json_city, json_city, selected, selected));
                                });
                                // 市区町村の選択値が空文字 = RESASで取得した市区町村とyubinbango.jsで取得した市区町村が一致しない
                                // jsonの市区町村のうちyubinbango.jsで取得した市区町村で終わるものが1件のみ見つかった場合、その市区町村を選択しておく
                                if ($('#city' + index).val() === '') {
                                    let matcher = json_cities.filter(json_city => {
                                        if (city.endsWith(json_city)) {
                                            return json_city;
                                        }
                                    });
                                    if (matcher.length === 1) {
                                        $('#city' + index).val(matcher[0]);
                                    }
                                }
                            });
                            $('#city' + index).trigger('change');
                        }
                        Object.getOwnPropertyDescriptor(HTMLInputElement.prototype,'value').set.call(target, city);
                    }
                })
            })($('.p-locality', table));
        };

        // 初期表示処理
        $('#addresses table').each((idx, table) => {
            attachFormEvents(table);
        });

        // 行追加ボタン
        $('#add-row').click(e => {
            let next_index = $('#addresses table').length;
            $.get(`/add-row?index=${next_index}`, html => {
                // レスポンスのhtmlテキスト→jqueryオブジェクト→DOMエレメントに変換
                html = $(html).get(0);
                attachFormEvents(html);
                $('.buttons').before(html);
                // DOM追加後、yubinbango.jsのDOMContentLoadedで実行してる処理を再度実行することで動的に追加したフォームに対応
                // ↓issue探したらあった
                // https://github.com/yubinbango/yubinbango/issues/6
                new YubinBango.MicroformatDom();
            });
        });
    });
</script>
@endsection

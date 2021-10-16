<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.css" rel="stylesheet" />
        <style>
            table {
                width: 400px;
            }
            table tr td {
                width: 300px;
            }
            table tr td input,
            table tr td select {
                width: 100%
            }
        </style>
    </head>
    <body>
        <form method="POST" action='/' class="h-adr">
            <span class="p-country-name" style="display:none;">Japan</span>
            @csrf
            <table>
                <tr>
                    <th>郵便番号</th>
                    <td>
                        <div class="input-group">
                            <input type="text" id="zip" name="zip" class="form-control p-postal-code" maxlength="7" placeholder="ハイフンなしで入力" value="{{ $data['zip'] ?? '' }}" />
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary" id="search"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <input type="hidden" class="p-region" />
                        <input type="hidden" class="p-locality" />
                    </td>
                </tr>
                <tr>
                    <th>都道府県</th>
                    <td>
                        <select id="pref" name="pref" class="form-control">
                            <option value="">選択してください</option>
                            @foreach (config('const.Prefs') as $pref_code => $pref_name)
                                <option value="{{ $pref_code }}"@if(isset($data['pref']) && $pref_code == $data['pref']) selected="selected"@endif>{{ $pref_name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>市区町村</th>
                    <td>
                        <select id="city" name="city" class="form-control">
                            <option value="">選択してください</option>
                            @foreach ($cities as $city_name)
                                <option value="{{ $city_name }}"@if(isset($data['city']) && $city_name == $data['city']) selected="selected"@endif>{{ $city_name }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>その他住所</th>
                    <td>
                        <input type="text" id="addr" name="addr" class="form-control p-street-address p-extended-address" value="{{ $data['addr'] ?? '' }}" />
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button class="btn btn-primary btn-block">送信</button>
                    </td>
                </tr>
            </table>
        </form>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
        <script type="text/javascript">
            $(() => {
                $('#pref, #city').select2({
                    theme: 'bootstrap4',
                });

                $('#zip').keyup(e => {
                    if ($(e.target).val().length !== 7) {
                        $('#pref, #city').val('').trigger('change');
                    }
                });

                $('#search').click(e => {
                    document.querySelector('#zip').dispatchEvent(new KeyboardEvent("keyup"));
                });

                // K:都道府県名 V:都道府県コード
                let prefs = @json(array_flip(config('const.Prefs')));

                @php
                // Yubinbangoでchangeなどのイベントは取得できないためhidden要素へのget、setアクセサーでselect2の制御を行う
                // Yubinbangoの変更は都道府県→市区町村→町域の順番で呼ばれる模様
                // 都道府県と市区町村の動的なselect2の変更は市区町村のset時に行う
                // 参考:https://teratail.com/questions/94990?sip=n0070000_019
                @endphp
                (target => {
                    Object.defineProperty(target, 'value',{
                        get : () => {
                            return Object.getOwnPropertyDescriptor(HTMLInputElement.prototype,'value').get.call(target)
                        },
                        set : city => {
                            if (city) {
                                let pref_code = prefs[$('.p-region').val()];
                                $('#pref').val(pref_code).trigger('change');
                                $.getJSON(`/json_data/${pref_code}.json`, json_cities => {
                                    json_cities.forEach(json_city => {
                                        let selected = json_city === city;
                                        $('#city').append(new Option(json_city, json_city, selected, selected));
                                    });
                                });
                                $('#city').trigger('change');
                            }
                            Object.getOwnPropertyDescriptor(HTMLInputElement.prototype,'value').set.call(target, city);
                        }
                    })
                })(document.querySelector('.p-locality'));
            });
        </script>
    </body>
</html>

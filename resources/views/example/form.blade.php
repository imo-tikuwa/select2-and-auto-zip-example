            <table class="mt-2 h-adr">
                <caption>
                    {{ $index + 1 }}個めのフォーム
                    <span class="p-country-name" style="display:none;">Japan</span>
                </caption>
                <tbody>
                    <tr>
                        <th>郵便番号</th>
                        <td>
                            <div class="input-group">
                                <input type="text" id="zip{{ $index }}" name="addresses[{{ $index }}][zip]" class="form-control form-control-sm p-postal-code" maxlength="7" placeholder="ハイフンなしで入力" value="{{ $data->zip ?? '' }}" />
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-secondary" id="search{{ $index }}" data-index="{{ $index }}">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" class="p-region" />
                            <input type="hidden" class="p-locality" data-index="{{ $index }}" />
                        </td>
                    </tr>
                    <tr>
                        <th>都道府県</th>
                        <td>
                            <select id="pref{{ $index }}" name="addresses[{{ $index }}][pref]" class="form-control form-control-sm">
                                <option value="">選択してください</option>
                                @foreach (config('const.Prefs') as $pref_code => $pref_name)
                                    <option value="{{ $pref_code }}"@if(isset($data->pref) && $pref_code == $data->pref) selected="selected"@endif>{{ $pref_name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>市区町村</th>
                        <td>
                            <select id="city{{ $index }}" name="addresses[{{ $index }}][city]" class="form-control form-control-sm">
                                <option value="">選択してください</option>
                                @if (isset($cities) && isset($cities[$index]) && is_array($cities[$index]))
                                    @foreach ($cities[$index] as $city_name)
                                        <option value="{{ $city_name }}"@if(isset($data->city) && $city_name == $data->city) selected="selected"@endif>{{ $city_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>その他住所</th>
                        <td>
                            <input type="text" id="addr{{ $index }}" name="addresses[{{ $index }}][addr]" class="form-control form-control-sm p-street-address p-extended-address" value="{{ $data->addr ?? '' }}" />
                        </td>
                    </tr>
                </tbody>
            </table>
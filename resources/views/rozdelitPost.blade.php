@extends('layouts.app2')

@section('content')
    <script>
        function showRange(i) {
            xname = "rangeDiv" + i;
            var x = document.getElementById(xname);
            x.style.display = "block";
            xname = "button" + i;
            var x = document.getElementById(xname);
            x.disabled = true;
        }

        function changeRange(i) {
            row1name = "firstRow" + i;
            row1 = document.getElementById(row1name);
            row2name = "secondRow" + i;
            row2 = document.getElementById(row2name);
            rowname = "row" + i;
            row = document.getElementById(rowname);
            rangeName = "range" + i;
            range = document.getElementById(rangeName);

            text = row.innerText;
            row1.innerText = text.substr(0, range.value);
            row2.innerText = text.substr(range.value);
        }

    </script>

    <div>
        <img src="{{asset('storage/logo-ael.jpg')}}" alt="logo">
        <div>
            <div style="width: 800px">
                <div>
                    <div style='text-align: center; background-color: lightgreen'><h2>KABELOVÉ ŠTÍTKY</h2>
                    </div>
                </div>
                <div>
                    <div style='text-align: center; background-color: lightgreen'></div>
                </div>
                <div style="text-align: center"><a href="{{route("SaveXlsGet")}}"><button>Download XLS</button></a></div>
                @php
                    $i = 0
                @endphp
                @foreach($stitkyDvakrat as $item)
                    <div>
                        <div id="firstRow{{$i}}" style="width: 370px; display: inline-block">{{$item[0]}}</div>
                        <div id="secondRow{{$i}}" style="width: 370px; display: inline-block">{{$item[1]}}</div>
                        <div style="width: 50px; display: inline-block">
                            <button onclick="showRange({{$i}})" id="button{{$i}}">edit</button>
                        </div>
                        <div id="row{{$i}}" hidden>{{$item[2]}}</div>
                        <div id="rangeDiv{{$i}}" hidden>
                            {{Form::open(['method'=>'POST', 'route'=>'saveRow', 'name' =>'form' . $i])}}
                            <input type="range" name="range" style="width: 700px" min="0" max="{{strlen($item[2])}}"
                                   value="{{strlen($item[0])}}" oninput="changeRange({{$i}})" id="range{{$i}}">
                            <input type="text" name="id" value="{{$i}}" hidden>
                            <button id="submit{{$i}}">Save</button>

                            {{Form::close()}}
                        </div>
                    </div>
                    @php
                        $i++
                    @endphp
                @endforeach
            </div>

        </div>
    </div>
@endsection

{{--@section('calendar_')--}}
    <div class="content-container">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <table class="table">
            <thead>
            <tr>
                <th></th>
                @for($d = 0; $d <= 6; $d++)
                    <th>
                        <span class="day">{{$days[$d]}}</span>
                    </th>
                @endfor
            </tr>
            </thead>
            <tbody>
            @for($h = 0; $h < 24; $h++)
                <tr class="table-dark">
                    <td class="hour" rowspan="5"><span>{{$h}}:00</span></td>
                </tr>
                @for($q = 0; $q < 4; $q++)
                    <tr class="table-dark td-hover">
                        @for($d = 0; $d <= 6; $d++)
                            <td rowspan="1" onclick="popup(this)" id="{{$d}}-{{$h}}-{{$q}}"
                                data-day="{{$d}}"
                                data-time="{{(strlen($h)>1)?$h:'0'.$h}}:{{(15*$q == 0)?'00':15*$q}}"></td>
                        @endfor
                    </tr>
                @endfor
            @endfor
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="timeEditor" tabindex="-1" role="dialog" aria-labelledby="timeEditorModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="title" id="dayTitle">Day Title</p>
                    <p class="close" data-dismiss="modal" aria-label="Close" onclick="$('#timeEditor').modal('hide')"><span aria-hidden="true">x</span></p>
                </div>
                <div class="modal-body">
                    <form id="timeForm">
                        <input type="hidden" name="id" id="id" value="0">
                        <input type="hidden" name="day" id="day" value="">

                        <label for="startTime">Start time</label>
                        <input type="time" name="startTime" id="startTime" min="00:00" max="23:59">

                        <label for="endTime">End time</label>
                        <input type="time" name="endTime" id="endTime" min="00:00" max="23:59">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="$('#timeEditor').modal('hide')">Close</button>
                    <button type="button" class="btn btn-primary" onclick="save()">Save</button>
                </div>
            </div>
        </div>
    </div>

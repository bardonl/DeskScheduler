    <div class="content-container">
        <div class="schedule-container">
            <div class="time-indicator-table">
                @for($h = 0; $h < 24; $h++)
                    <div class="hour-indicator">
                        <span>{{$h}}:00</span>
                    </div>
                @endfor
            </div>
            <div class="days-container">
                @for($d = 0; $d <= 6; $d++)
                <div class="day">
                    <div class="title">
                        <span>{{$days[$d]}}</span>
                    </div>
                    <div class="hours-container">
                        @for($h = 0; $h < 24; $h++)
                            @for($q = 0; $q < 4; $q++)
                                @if(key_exists($d,$scheduled))
                                    @foreach($scheduled[$d] as $scheduledItem)
                                        @if($scheduledItem['h_start'] == $h && $scheduledItem['q_start'] == $q)
                                            <div class="scheduled" onclick="popup(this, false)"
                                                 id="{{$scheduledItem['day']}}-{{$scheduledItem['h_start']}}-{{$scheduledItem['q_start']}}-scheduled"
                                                 data-schedule_id="{{$scheduledItem['id']}}"
                                                 data-day="{{$scheduledItem['day']}}"
                                                 data-time="{{$scheduledItem['start_time']}}"
                                                 data-endtime="{{$scheduledItem['end_time']}}"
                                                 style="height:{{$scheduledItem['q_amount']*15}}"
                                            ></div>
                                        @endif
                                    @endforeach
                                @endif
                                <div class="hour"
                                     onclick="popup(this, true)" id="{{$d}}-{{$h}}-{{$q}}"
                                     data-day="{{$d}}"
                                     data-time="{{(strlen($h)>1)?$h:'0'.$h}}:{{(15*$q == 0)?'00':15*$q}}">
                                </div>
                            @endfor
                        @endfor
                    </div>
                </div>

                @endfor
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="timeEditor" tabindex="-1" role="dialog" aria-labelledby="timeEditorModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <p class="title" id="dayTitle">Day Title</p>
                    <p class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></p>
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
                    <button type="button" class="btn btn-default close" data-bs-function="close" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger delete" data-bs-function="delete" onclick="deleteEntry()">delete</button>
                    <button type="button" class="btn btn-primary save" id="save" data-bs-dismiss="modal" data-bs-function="save" onclick="save()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="schedule-active" onclick="popup(this, false)">

    </div>


var days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

function popup(data, copy = false) {
    if(copy) {
        $('.schedule-active')
            .clone()
            .insertBefore($(data))
            .attr('id', $(data).attr('id') + '-scheduled')
            .attr('class', 'scheduled');
        $('.delete').css("display", "none");
    } else {
        $('#id').val($(data).data('schedule_id'));
        $('.delete').css("display", "block");
    }

    let id = $(data).attr('id');

    if (!~id.indexOf("scheduled")){
        id = id + '-scheduled'
    }

    $('#' + id)
        .clone()
        .insertBefore($(data))
        .attr('id', id + '-copy')
        .attr('class', 'scheduled')
        .css("display", "none");

    $("<input type='hidden' id='original-id'/>").insertBefore('input#id').val($(data).attr('id'));
    var timeData = $(data).data('time');
    var dayData = $(data).data('day');

    $('#dayTitle').text(days[dayData]);
    $('#startTime').val(timeData);

    if(!$(data).data('endtime')){
        var endTime = moment(timeData,'HH:mm').add(15, 'minutes').format('HH:mm');
        $('#endTime').val(endTime);
    } else {
        $('#endTime').val($(data).data('endtime'));
    }

    $('#endTime').attr({'min':timeData});
    $('#day').val(dayData);

    $('#timeEditor').modal('show');
}

function close(saved = false)
{
    let originalId = $('#original-id').val();

    if (!~originalId.indexOf("scheduled")){
        if(!saved){
            $('#' + originalId + '-scheduled').remove();
            $('#' + originalId + '-scheduled-copy').remove();
        }
        originalId = originalId + '-scheduled'
    }

    $('#original-id').remove();

    if(!saved){
        $('#' + originalId).remove();
        $('#' + originalId + '-copy')
            .css('display','block')
            .attr('id',originalId);
    }

    $('#' + originalId + '-copy').remove();
}

function save(){
    let timeForm = document.getElementById('timeForm');
    let formData = new FormData(timeForm);

    $.ajax({
        type: "POST",
        url: '/api/schedules/save',
        data: {
            'id':formData.get('id'),
            'day':formData.get('day'),
            'startTime':formData.get('startTime'),
            'endTime':formData.get('endTime')
        },
        success: function(data){
            let idDiff = fetchIdAndDiff(formData.get('day'),formData.get('startTime'),formData.get('endTime'),'-scheduled')
            $('#'+idDiff.id)
                .attr('data-schedule_id',data.id)
                .attr('data-day',formData.get('day'))
                .attr('data-time',formData.get('startTime'))
                .attr('data-endtime',formData.get('endTime'));
        },
        error: function(){
            alert('Something went wrong while saving the time!')
        }
    });
}

function deleteEntry()
{
    let formData = new FormData(timeForm);
    let id = formData.get('id');
    let originalId = $('#original-id').val();

    $.ajax({
        type: "DELETE",
        url: '/api/schedules/delete',
        data: {
            'id': id
        },
        success: function () {
            $('#timeEditor').modal('hide');
            $('#'+originalId).remove();
        },
        error: function () {
            alert('error')
        }
    })
}

$('#endTime').on('change',function(){
    let data = fetchIdAndDiff($('#day').val(),$('#startTime').val(),$('#endTime').val(),'-scheduled')
    $('#'+data.id).height(15*data.diff);
})

$('#startTime').on('change',function(){
    let originalId = $('#original-id').val();

    if (!~originalId.indexOf("scheduled")){
        originalId = originalId + '-scheduled'
    }

    $('#'+ originalId).remove();

    let data = fetchIdAndDiff($('#day').val(),$('#startTime').val(),$('#endTime').val(),'-scheduled')

    var idSplit = data.id.split('-scheduled');

    $('.schedule-active')
        .clone()
        .insertBefore($('#'+idSplit[0]))
        .attr('id', data.id)
        .attr('class', 'scheduled').attr('data-schedule_id',data.id)
        .attr('data-day',$('#day').val())
        .attr('data-time',$('#startTime').val())
        .attr('data-endtime',$('#endTime').val());

    $('#'+data.id).height(15*data.diff);
})

function fetchIdAndDiff(day,firstTime, secondTime, extra = false){
    var diff = moment(secondTime,'HH:mm').subtract(firstTime).format('HH:mm');
    var tDiff = diff.split(':');
    var qDiff = parseInt(tDiff[1])/15;
    qDiff = (tDiff[0]*4) + qDiff;

    var tFirst = firstTime.split(':');
    var qFirst = parseInt(tFirst[1])/15;

    return {'id':day+'-'+parseInt(tFirst[0])+'-'+qFirst + extra,'diff':qDiff};
}

var timeEditor = document.getElementById('timeEditor')
timeEditor.addEventListener('hide.bs.modal', function (event) {
    var $activeElement = $(document.activeElement);

    if ($activeElement.is('[data-bs-function=save]')) {
        close(true);
    }

    close();
})

var warning = document.getElementById('warning')
warning.addEventListener('hide.bs.modal', function (event) {
    var $activeElement = $(document.activeElement);

    if ($activeElement.is('[data-bs-function=save]')) {
        close(true);
    }

    close();
})


$(document).ready(function(){
    getInidicatorPosition();
    let date = new Date();
    let sec = date.getSeconds();
    setTimeout(()=>{
        setInterval(()=>{
            getInidicatorPosition();
        }, 60 * 1000);
    }, (60 - sec) * 1000);
});

function getInidicatorPosition()
{
    var containerHeight = $('.hours-container').height();
    var hour = moment().format('HH');
    var day = $('.indicator').attr('data-day');
    var quarter = Math.ceil(moment().format('mm')/15);
    var indicatorHeight = $('.indicator').height() / 2;
    var pos = (containerHeight/(24 * 60)) * (parseInt(moment().format('HH') * 60) + parseInt(moment().format('mm')));
    var finalPos = pos + indicatorHeight - 1;
    $('.indicator').css('top',finalPos);

    var position = $('#'+day+'-'+hour+'-'+quarter+'-scheduled').position()
    if(position){
        var posDiff = position.top - pos - 5;
        console.log(position.top);
        console.log(posDiff%5);
        if(posDiff <= 15){
            if(posDiff%5 == 0){
                $('#warning').modal('show');

            }
            const audio = new Audio("../sounds/notification.wav");
                audio.play();
            console.log(posDiff);
        }
    }


}

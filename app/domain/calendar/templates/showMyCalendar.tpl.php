<?php
    defined('RESTRICTED') or die('Restricted access');
    if(!isset($_SESSION['submenuToggle']["myCalendarView"])) {
        $_SESSION['submenuToggle']["myCalendarView"] = "dayGridMonth";
    }
?>

<?php $this->dispatchTplEvent('beforePageHeaderOpen'); ?>
<div class="pageheader">
    <?php $this->dispatchTplEvent('afterPageHeaderOpen'); ?>
    <div class="pageicon"><span class="fa <?php echo $this->getModulePicture() ?>"></span></div>
    <div class="pagetitle">
        <h5><?php echo $this->__('headline.calendar'); ?></h5>
        <h1><?php echo $this->__('headline.my_calendar'); ?></h1>
    </div>
    <?php $this->dispatchTplEvent('beforePageHeaderClose'); ?>
</div><!--pageheader-->
<?php $this->dispatchTplEvent('afterPageHeaderClose'); ?>

<div class="maincontent">
    <div class="maincontentinner">

        <div class="row">
            <div class="col-md-4">
                <a href="<?=BASE_URL ?>/calendar/showMyCalendar/#/calendar/addEvent" class="btn btn-primary formModal"><i class='fa fa-plus'></i> <?=$this->__('buttons.add_event')?></a>
            </div>
            <div class="col-md-4">
                <div class="fc-center center" id="calendarTitle" style="padding-top:5px;">
                    <h2>..</h2>
                </div>
            </div>
            <div class="col-md-4">
                <a href="<?=BASE_URL?>/calendar/export" class="btn btn-default right exportModal">Export</a>
                <button class="fc-next-button btn btn-default right" type="button" style="margin-right:5px;">
                    <span class="fc-icon fc-icon-chevron-right"></span>
                </button>
                <button class="fc-prev-button btn btn-default right" type="button" style="margin-right:5px;">
                    <span class="fc-icon fc-icon-chevron-left"></span>
                </button>

                <button class="fc-today-button btn btn-default right" style="margin-right:5px;">today</button>


                <select id="my-select" style="margin-right:5px;" class="right">
                    <option class="fc-timeGridDay-button fc-button fc-state-default fc-corner-right" value="timeGridDay" <?=$_SESSION['submenuToggle']["myCalendarView"] == 'timeGridDay' ? "selected" : '' ?>>Day</option>
                    <option class="fc-timeGridWeek-button fc-button fc-state-default fc-corner-right" value="timeGridWeek" <?=$_SESSION['submenuToggle']["myCalendarView"] == 'timeGridWeek' ? "selected" : '' ?>>Week</option>
                    <option class="fc-dayGridMonth-button fc-button fc-state-default fc-corner-right" value="dayGridMonth" <?=$_SESSION['submenuToggle']["myCalendarView"] == 'dayGridMonth' ? "selected" : '' ?>>Month</option>
                    <option class="fc-multiMonthYear-button fc-button fc-state-default fc-corner-right" value="multiMonthYear" <?=$_SESSION['submenuToggle']["myCalendarView"] == 'multiMonthYear' ? "selected" : '' ?>>Year</option>
                </select>

            </div>

        </div>



        <div id="calendar"></div>

    </div>
</div>


<script type='text/javascript'>

    <?php $this->dispatchTplEvent('scripts.afterOpen'); ?>


    jQuery(document).ready(function() {

        //leantime.calendarController.initCalendar(events);
        leantime.calendarController.initExportModal();

    });



    var events = [<?php foreach ($this->get('calendar') as $calendar) : ?>
        {

            title: <?php echo json_encode($calendar['title']); ?>,

            start: new Date(<?php echo
                $calendar['dateFrom']['y'] . ',' .
                ($calendar['dateFrom']['m'] - 1) . ',' .
                $calendar['dateFrom']['d'] . ',' .
                $calendar['dateFrom']['h'] . ',' .
                $calendar['dateFrom']['i'] ?>),
            <?php if (isset($calendar['dateTo'])) : ?>
            end: new Date(<?php echo
                $calendar['dateTo']['y'] . ',' .
                ($calendar['dateTo']['m'] - 1) . ',' .
                $calendar['dateTo']['d'] . ',' .
                $calendar['dateTo']['h'] . ',' .
                $calendar['dateTo']['i'] ?>),
            <?php endif; ?>
            <?php if ((isset($calendar['allDay']) && $calendar['allDay'] === true)) : ?>
            allDay: true,
            <?php else : ?>
            allDay: false,
            <?php endif; ?>
            enitityId: <?php echo $calendar['id'] ?>,
            <?php if (isset($calendar['eventType']) && $calendar['eventType'] == 'calendar') : ?>
            url: '<?=CURRENT_URL ?>#/calendar/editEvent/<?php echo $calendar['id'] ?>',
            color: 'var(--accent2)',
            enitityType: "event",
            <?php else : ?>
            url: '<?=CURRENT_URL ?>#/tickets/showTicket/<?php echo $calendar['id'] ?>?projectId=<?php echo $calendar['projectId'] ?>',
            color: 'var(--accent1)',
            enitityType: "ticket",
            <?php endif; ?>
        },
        <?php endforeach; ?>];



        document.addEventListener('DOMContentLoaded', function() {
            const heightWindow = jQuery("body").height() - 190;

            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                    height:heightWindow,
                    initialView: '<?=$_SESSION['submenuToggle']["myCalendarView"] ?>',
                    events: events,
                    editable: true,
                    headerToolbar: false,

                    nowIndicator: true,
                    bootstrapFontAwesome: {
                        close: 'fa-times',
                        prev: 'fa-chevron-left',
                        next: 'fa-chevron-right',
                        prevYear: 'fa-angle-double-left',
                        nextYear: 'fa-angle-double-right'
                    },
                    eventDrop: function (event) {
                        console.log(event.event);
                        console.log(event.event.startStr);
                        console.log(event.event.endStr);
                        console.log(event.event.extendedProps.enitityType);
                        console.log(event.event.extendedProps.enitityId);

                        if(event.event.extendedProps.enitityType == "ticket") {
                            jQuery.ajax({
                                type : 'PATCH',
                                url  : leantime.appUrl + '/api/tickets',
                                data : {
                                    id: event.event.extendedProps.enitityId,
                                    editFrom: event.event.startStr,
                                    editTo: event.event.endStr
                                }
                            });

                        }else if(event.event.extendedProps.enitityType == "event") {

                            jQuery.ajax({
                                type : 'PATCH',
                                url  : leantime.appUrl + '/api/calendar',
                                data : {
                                    id: event.event.extendedProps.enitityId,
                                    dateFrom: event.event.startStr,
                                    dateTo: event.event.endStr
                                }
                            })
                        }
                    },
                    eventResize: function (event) {
                        console.log(event.event);
                        console.log(event.event.startStr);
                        console.log(event.event.endStr);
                        console.log(event.event.extendedProps.enitityType);
                        console.log(event.event.extendedProps.enitityId);

                        if(event.event.extendedProps.enitityType == "ticket") {
                            jQuery.ajax({
                                type : 'PATCH',
                                url  : leantime.appUrl + '/api/tickets',
                                data : {
                                    id: event.event.extendedProps.enitityId,
                                    editFrom: event.event.startStr,
                                    editTo: event.event.endStr
                                }
                            })
                        }else if(event.event.extendedProps.enitityType == "event") {

                            jQuery.ajax({
                                type : 'PATCH',
                                url  : leantime.appUrl + '/api/calendar',
                                data : {
                                    id: event.event.extendedProps.enitityId,
                                    dateFrom: event.event.startStr,
                                    dateTo: event.event.endStr
                                }
                            })
                        }

                    },
                    eventMouseEnter: function() {
                    }
                }
                );
            calendar.setOption('locale', leantime.i18n.__("language.code"));
            calendar.render();
            calendar.scrollToTime( 100 );
            jQuery("#calendarTitle h2").text(calendar.getCurrentData().viewTitle);

            jQuery('.fc-prev-button').click(function() {
                calendar.prev();
                calendar.getCurrentData()
                jQuery("#calendarTitle h2").text(calendar.getCurrentData().viewTitle);
            });
            jQuery('.fc-next-button').click(function() {
                calendar.next();
                jQuery("#calendarTitle h2").text(calendar.getCurrentData().viewTitle);
            });
            jQuery('.fc-today-button').click(function() {
                calendar.today();
                jQuery("#calendarTitle h2").text(calendar.getCurrentData().viewTitle);
            });
            jQuery("#my-select").on("change", function(e){

                calendar.changeView(jQuery("#my-select option:selected").val());

                jQuery.ajax({
                    type : 'PATCH',
                    url  : leantime.appUrl + '/api/submenu',
                    data : {
                        submenu : "myCalendarView",
                        state   : jQuery("#my-select option:selected").val()
                    }
                });

            });
        });





    <?php $this->dispatchTplEvent('scripts.beforeClose'); ?>

</script>

<!-- Bootstrap datetimepicker plugins -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.43/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function () {

        var date = new Date();
        var year = date.getFullYear();

    	$('#datetimepicker1').datetimepicker({
            format: 'L',
            minDate: '1/1/' + year,
        });
    	$('#datetimepicker2').datetimepicker({
            format: 'L',
            minDate: '1/1/' + year,
        });
        $('#datetimepicker3').datetimepicker({
            format: 'LT',
        });
        $('#datetimepicker4').datetimepicker({
            format: 'LT',
        });
        $('#datetimepicker11').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker21').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker12').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker22').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker13').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker23').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker14').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker24').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker15').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker25').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker16').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker26').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker17').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker27').datetimepicker({
            format: 'LT'
        });
        $('#datetimepicker5').datetimepicker({
            format: 'L',
            minDate: '1/1/' + year,
        });
        $('#datetimepicker6').datetimepicker({
            format: 'L',
            minDate: '1/1/' + year,
        });
    });
</script>
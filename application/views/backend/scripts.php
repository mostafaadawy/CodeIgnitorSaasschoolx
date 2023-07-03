    <link rel="stylesheet" href="<?php echo base_url();?>assets/js/wysihtml5/bootstrap-wysihtml5.css">
    <script src="<?php echo base_url();?>style/cms/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/moment/moment.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/tether/dist/js/tether.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/chart.js/dist/Chart.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/select2/dist/js/select2.full.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/ckeditor/ckeditor.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/bootstrap-validator/dist/validator.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/dropzone/dist/dropzone.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/editable-table/mindmup-editabletable.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/bootstrap/js/dist/util.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/bootstrap/js/dist/tab.js"></script>
    <script src="<?php echo base_url();?>style/cms/js/main.js?version=3.2.1"></script>
    <script src="<?php echo base_url();?>style/cms/js/toastr.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/dragula.js/dist/dragula.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/bootstrap/js/dist/modal.js"></script>
    <script src="<?php echo base_url();?>style/cms/js/custom-file-input.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/bootstrap/js/dist/tooltip.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url();?>style/tinymce/tinymce.min.js"></script>
    <script src="<?php echo base_url();?>style/cms/bower_components/bootstrap-clockpicker/bootstrap-clockpicker.min.js"></script>	
	
	
    
	<script src="<?php echo base_url();?>style/cms/bower_components/mselect/multiple-select.js"></script>

 <script>
    $(function () {
        $('select[multiple].active.3col').multiselect({
            columns: 3,
            placeholder: 'Select',
            search: true,
            searchOptions: {
                'default': 'Search'
            },
            selectAll: true
        });

    });
</script>    

	
<script type="text/javascript">
    $('#birthday').daterangepicker({
        singleDatePicker: true,
		timePicker: false,
        showDropdowns: true,
		maxDate : moment().startOf('day'),
		minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),20),
		//minDate : moment().startOf('day'),
		//startDate : moment().startOf('day'),
		locale: {
            format: 'DD/MM/YYYY'
        }
    });
</script>

<script type="text/javascript">
    $('#start_date').daterangepicker({
        singleDatePicker: true,
		timePicker: false,
        showDropdowns: true,
		minDate : moment().startOf('day'),
		minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),20),
		//minDate : moment().startOf('day'),
		//startDate : moment().startOf('day'),
		locale: {
            format: 'DD/MM/YYYY'
        }
    });
</script>

<script type="text/javascript">
    $('#end_date').daterangepicker({
        singleDatePicker: true,
		timePicker: false,
        showDropdowns: true,
		minDate : moment().startOf('day'),
		minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),20),
		//minDate : moment().startOf('day'),
		//startDate : moment().startOf('day'),
		locale: {
            format: 'DD/MM/YYYY'
        }
    });
</script>
	
<script type="text/javascript">
    $('input.single-daterange-2').daterangepicker({
        singleDatePicker: true,
		timePicker: true,
        showDropdowns: true,
		//startDate : moment().startOf('day'),
		minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),20),
		locale: {
            format: 'YYYY-MM-DD HH:mmm'
        }
    });
</script>	

<script type="text/javascript">
    $('input.single-daterange').daterangepicker({
        singleDatePicker: true,
		timePicker: false,
        showDropdowns: true,
		minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),20),
		//minDate : moment().startOf('day'),
		//startDate : moment().startOf('day'),
		locale: {
            format: 'DD/MM/YYYY'
        }
    });
</script>

<script type="text/javascript">
$(document).ready(function () {
	 $('#availableto').daterangepicker({
        singleDatePicker: true,
		timePicker: false,
        showDropdowns: true,
		autoUpdateInput: true,
		minDate: moment().startOf('day'),
		locale: {
            format: 'DD/MM/YYYY'
        }
    });
});
</script>

<script type="text/javascript">
$(document).ready(function () {
    $('#availablefrom').daterangepicker({
        singleDatePicker: true,
		timePicker: false,
        showDropdowns: true,
		minDate : moment().startOf('day'),
		locale: {
            format: 'DD/MM/YYYY'
        }
    });
});
</script>


<script type="text/javascript">
$('.clockpicker').clockpicker({
    placement: 'top',
    align: 'left',
	
    donetext: 'Done'
});
</script>
<script type="text/javascript">
    function readURL(input) 
    {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
             $('div.slide').attr('style', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgpre").change(function()
    {
        readURL(this);
    });
</script>
 <script>
    $(document).ready(function() {

        if ($("#mymce").length > 0) {
            tinymce.init({
                selector: "textarea#mymce",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",

            });
        }
    });
    </script>
<script type="text/javascript">
    function readLogo(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#logo').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#logoprev").change(function()
    {
        readLogo(this);
    }); 
</script>
<script type="text/javascript">
    function readLogoColor(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#logocolor').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#logocolorprev").change(function()
    {
        readLogoColor(this);
    }); 
</script>
<script type="text/javascript">
    function readAvatar(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#avatar').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#avatarprev").change(function()
    {
        readAvatar(this);
    }); 
</script>
<script type="text/javascript">
    function readBG(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#bglogin').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#bgloginprev").change(function()
    {
        readBG(this);
    }); 
</script>
<script type="text/javascript">
    function readLogoW(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#logow').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#logowprev").change(function()
    {
        readLogoW(this);
    }); 
</script>
<script type="text/javascript">
    function readicon(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#icon').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#iconprev").change(function()
    {
        readicon(this);
    }); 
</script>
<script type="text/javascript">
    function readiconW(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#iconw').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#iconwprev").change(function()
    {
        readiconW(this);
    }); 
</script>
<script type="text/javascript">
    function readfavicon(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#favicon').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#faviconprev").change(function()
    {
        readfavicon(this);
    }); 
</script>
<script type="text/javascript">
function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#ava').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
$("#imgpre").change(function(){
    readURL(this);
}); 

</script>

<script type="text/javascript">
function readURL1(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#ava').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#imgpre_super").change(function(){
    readURL1(this);
}); 


</script>


<script type="text/javascript">
function readURL2(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#ava_driver').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#imgpre_driver").change(function(){
    readURL2(this);
}); 
</script>

<script type="text/javascript">
    function readSlide1(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#slider1').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#slider1prev").change(function()
    {
        readSlide1(this);
    }); 
</script>
<script type="text/javascript">
    function readSlide2(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#slider2').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#slider2prev").change(function()
    {
        readSlide2(this);
    }); 
</script>




<script type="text/javascript">
    function readSlide3(input) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) 
            {
                $('#slider3').attr('src', e.target.result);
            }        
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#slider3prev").change(function()
    {
        readSlide3(this);
    }); 
</script>



<?php if ($this->session->flashdata('flash_message') != ""):?>
<script type="text/javascript">
    toastr.success('<?php echo $this->session->flashdata("flash_message");?>');
</script>
<?php endif;?>
<?php if ($this->session->flashdata('error_message') != ""):?>
<script type="text/javascript">
    toastr.error('<?php echo $this->session->flashdata("error_message");?>');
</script>
<?php endif;?>

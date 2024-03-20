<script>
    window.onload = function() {
        $("#contactForm input,#contactForm textarea").jqBootstrapValidation({
            preventSubmit: true,
            submitSuccess: function($form, event) {
                $this.prop("disabled", true);
            },
            filter: function() {
                return $(this).is(":visible");
            }
        });

        $("a[data-toggle=\"tab\"]").click(function(e) {
            e.preventDefault();
            $(this).tab("show");
        });

        $('#name').focus(function () {
            $('#success').html('');
        });    

        $('#name').change(function() {
            $('#company_name').val($(this).val());
        });  
    };
</script>

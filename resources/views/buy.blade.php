@section('buy_form')
    <script>

        if(window.attachEvent) {
            window.attachEvent('onload', getPrice);
        } else {
            if(window.onload) {
                var curronload = window.onload;
                var newonload = function(evt) {
                    curronload(evt);
                    getPrice(evt);
                };
                window.onload = newonload;
            } else {
                window.onload = getPrice;
            }
        }



        function getPrice() {

            document.getElementById("add").disabled = true;
            document.getElementById("price").innerHTML = "...";

            if (document.getElementById("amount").value !== ""){
                var url = "../price/" + document.getElementById("amount").value + "/1";
                try{
                    $.get( url , function( data ) {

                                if (data.ret !== ""){
                                    document.getElementById("price").className = data.ret;
                                    document.getElementById("add").disabled = true;
                                }else{
                                    document.getElementById("price").className = "";
                                    document.getElementById("add").disabled = false;
                                }

                        document.getElementById("price").innerHTML = "$" + data.price;



                            })
                            .fail(function() {
                                document.getElementById("price").innerHTML = "...";
                                document.getElementById("add").disabled = true;
                            });
                }

                catch(err){
                    document.getElementById("price").innerHTML = "...";
                    document.getElementById("add").disabled = true;
                }

            }


        }
    </script>




<script src="js/bootstrap-number-input.js" ></script>
<script>
    $('#amount').bootstrapNumber();
</script>

@stop
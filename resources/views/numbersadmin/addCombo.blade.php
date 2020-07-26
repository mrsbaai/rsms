@extends('layouts.numbersadmin')

@section('head')
    <title>Receive-SMS :: Add combo</title>
@stop


@section('content')




    <div class="container width-fix col-sm-12">
 

            <h3>Add Combo user:pass:number</h3><br/>
            <div class="container width-fix col-sm-12">
                {{ Form::open(array('action' => 'adminController@addCombo', 'id' => 'add-combo'))}}
                <div class="col-sm-12">
                    <textarea class="form-control" id="combo" name="combo" rows="15"></textarea>
                    
                </div>
                <br/><br/>
                <div class="col-sm-12">
                    <p class="text-center">
                        <br/><br/><br/><br/>
                        <button class="btn btn-lg btn-success" type="submit" id="add">Add Combo</button>
                    </p> 
                </div>
                {{ Form::close() }}
                

        </div>
    </div>




@stop



@section('bottom')
    <script src="js/bootstrap-number-input.js" ></script>
    <script>
        $('#amount').bootstrapNumber();
        $('#period').bootstrapNumber();
    </script>
@stop

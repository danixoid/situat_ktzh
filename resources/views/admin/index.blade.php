@extends('layouts.app')

@section('content')


    <?php
    function recursive($org_id) {

        if(\App\Org::whereOrgId($org_id)->count() > 0) :
            echo '<ul class="">';
            foreach(\App\Org::whereOrgId($org_id)->get() as $org):
                echo '<li class="text-danger">' . $org->id . ' ' . $org->name  . ' : [' . $org->positions->count() . ']';

                foreach ($org->positions as $position):
                    echo ' <span title="' . $position->user->name . '" class="label label-success">' . $position->id . ' ' .
                        $position->name  . '</span>';
                endforeach;


                echo '</li>';

                echo recursive($org->id);
            endforeach;
            echo '</ul>';

        endif;
    }?>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    {!! recursive(null) !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

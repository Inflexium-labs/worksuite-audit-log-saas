@extends('layouts.app')

@section('page-title')
    <div class="row bg-title">
        <div class="col-lg-8 col-md-5 col-sm-6 col-xs-12">
            <h4 class="page-title"><i class="{{ $pageIcon ?? 'fa fa-history' }}"></i> {{ $pageTitle ?? __('Audit Log') }}
            </h4>
        </div>
    </div>
<style>


.wrap {
  box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
  border-radius: 4px;
}


.panel {
  border-width: 0 0 1px 0;
  border-style: solid;
  border-color: #fff;
  background: none;
  box-shadow: none;
}

.panel:last-child {
  border-bottom: none;
}

.panel-heading {
  background-color: #009688;
  border-radius: 0;
  border: none;
  color: #fff;
  padding: 0;
}

.panel-title a {
  display: block;
  color: #fff;
  position: relative;
  font-size: 12px;
  font-weight: 400;
  text-transform: capitalize

}

.panel-body {
  background: #fff;
}

.panel-group{
    margin-bottom: 0px
}
.panel:last-child .panel-heading {
  border-radius: 0 0 4px 4px;
  transition: border-radius 0.3s linear 0.2s;
  
}

.panel:last-child .panel-heading.active {
  border-radius: 0;
  transition: border-radius linear 0s;
}
/* #bs-collapse icon scale option */

.panel-heading a:before {
  content: '\e146';
  position: absolute;
  font-family: 'Material Icons';
  right: 5px;
  top: -4px;
  font-size: 24px;
  transition: all 0.5s;
  transform: scale(1);
}

.panel-heading.active a:before {
  content: ' ';
  transition: all 0.5s;
  transform: scale(0);
}

#bs-collapse .panel-heading a:after {
  content: ' ';
  font-size: 24px;
  position: absolute;
  font-family: 'Material Icons';
  right: 5px;
  top: 10px;
  transform: scale(0);
  transition: all 0.5s;
}

#bs-collapse .panel-heading.active a:after {
  content: '\e909';
  transform: scale(1);
  transition: all 0.5s;
}
/* #accordion rotate icon option */

#accordion .panel-heading a:before {
  content: '\e316';
  font-size: 24px;
  position: absolute;
  font-family: 'Material Icons';
  right: 5px;
  top: 10px;
  transform: rotate(180deg);
  transition: all 0.5s;
}

#accordion .panel-heading.active a:before {
  transform: rotate(0deg);
  transition: all 0.5s;
}

</style>
@endsection

@push('head-script')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bower_components/custom-select/custom-select.css') }}">
@endpush

@section('content')
    <div class="row">

        <div class="col-md-12">
            <div class="white-box">
                @section('filter-section')
                <form>
                  <div class="form-group">
                    <select name="model_name" class="form-control">
                      <option selected value="">Select Model</option>
                      @foreach ($logModels as $logModel)
                      <option {{ request('model_name') == $logModel->key ? 'selected' : '' }} value="{{ $logModel->key }}">{{ $logModel->name }}</option>                        
                      @endforeach
                    </select>
                   </div>
                    <div class="form-group">
                        <select name="year" id="year" class="form-control">
                            @foreach (range(date("Y"), 2015) as $year)
                                <option value="{{ $year }}"
                                    {{ request('year') == $year ? 'selected' : '' }}
                                    {{ request('year') == null && $year == date('Y') ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <select name="month" id="month" class="form-control">
                            @for($i = 1 ; $i <= 12; $i++)
                                <option value="{{ $i }}"
                                    {{ request('month') == $i ? 'selected' : '' }}
                                    {{ request('month') == null && $i == date('m') ? 'selected' : '' }}>
                                    {{ date("F",strtotime((request()->year ?? date("Y"))."-".$i."-01")) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <button class="btn btn-success btn-sm col-md-5">
                                <i class="fa fa-check"></i>
                                @lang('app.apply')
                            </button>
                            <a href="?" class="btn btn-inverse col-md-5 btn-sm col-md-offset-1">
                                <i class="fa fa-refresh"></i>
                                @lang('app.reset')
                            </a>
                        </div>
                    </div>
                </form>
                @endsection
                
                <div class="table-responsive">
                    {!! $dataTable->table(['class' => 'table table-bordered table-hover toggle-circle default footable-loaded footable']) !!}
                </div>
            </div>
        </div>
    </div>
    <!-- .row -->
@endsection

@push('footer-script')
<script src="{{ asset('plugins/bower_components/datatables/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="{{ asset('js/datatables/buttons.server-side.js') }}"></script>
{!! $dataTable->scripts() !!}

<script>
$(document).ready(function() {
  $('.collapse.in').prev('.panel-heading').addClass('active');
  $('#accordion, #bs-collapse')
    .on('show.bs.collapse', function(a) {
      $(a.target).prev('.panel-heading').addClass('active');
    })
    .on('hide.bs.collapse', function(a) {
      $(a.target).prev('.panel-heading').removeClass('active');
    });
});
</script>
@endpush
<div class="col-md-12 col-sm-12">
    <div class="panel-group wrap mb-0"  id="bs-collapse">
        @foreach ($properties as $key => $property)
        <div class="panel">
            <div class="panel-heading">
                <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#" href="#{{ $id }}-col-{{ $loop->iteration }}">
                  {{ str_replace('_',' ',$key) }}
                </a>
                </h4>
            </div>
            <div id="{{ $id }}-col-{{ $loop->iteration  }}" class="panel-collapse collapse">
                <div class="panel-body">
                  <div>
                      <h3 class="font-weight">Orginal Value</h3>
                      <p>{{ $property['original'] }}</p>
                  </div>
                  <hr>
                  <div>
                    <h3 class="font-weight">Chnage Value</h3>
                    <p>{{ $property['changes'] }}</p>
                </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

 
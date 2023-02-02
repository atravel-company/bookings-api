<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <section>
        <label><label style="color:green;">Internal </label> Remark:</label>
        <div class="input-group">

            <input type="text" name="remark_interno" class="form-control w3-block" placeholder="Insert remark..."
                data-pedido-produto-id="{{ $model->pedido_produto_id }}">

            <span class="input-group-btn">
                <button class="btn btn-success" type="button" style="margin-left: 10px"
                    onClick="salvarRemarkInterno({{ $model->pedido_produto_id }}, '{{ $modelType }}')">
                    Send
                </button>

                @if (in_array($user->email, $users_array))
                    <button style="margin-left: 2%" data-pedido_id='{{ $model['pedido_produto_id'] }}' class="btn btn-warning"
                        type="button" onClick="abrirModalEditarRemarkInterno({{ $model['pedido_produto_id'] }}, '{{ $modelType }}')">
                        Edit Remark
                    </button>
                @endif
            </span>
        </div>
        <!-- /input-group -->

        <label style="margin-top:10px">Remarks:</label>

        <div class="remark-box" id="remark-box-internal-{{ $model['pedido_produto_id'] }}">
            @php $remark = ""; @endphp
            @foreach ($modelAll[$key][$key1] as $Pedido)
                @if ($Pedido->remark_internal !== null)
                    @php $remark .= $Pedido->remark_internal."<br>"; @endphp
                @endif
            @endforeach
            {!! $remark !!}
        </div>
    </section>
</div>

<td class="text-center">
    @if(is_null($finalizado) || $finalizado == "false")
    <span class="m-switch m-switch--outline m-switch--icon m-switch--success" style ="vertical-align: -50px;" >
        <label>
            <input type="checkbox" class="check-compra" data-id="{{$compra->id}}">
            <span></span>
        </label>
    </span>
    @endif
</td>


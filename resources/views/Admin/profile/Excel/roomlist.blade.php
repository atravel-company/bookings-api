<table>
    <thead>
        <tr>
        <td>TOPICOS E DESCOBERTAS LDA</td>
        <td></td>
        </tr>
        <tr>
        <td>Telef.(+351) 917 250 405</td>
        <td>Av. da Liberdade,</td>
        </tr>
        <tr>
        <td>Mobile.(+351) 912 032 695</td>
        <td>243, 4ºA </td>
        </tr>
        <tr>
        <td>reservations: incoming@atravel.pt</td>
        <td>1250-143 Lisboa</td>
        </tr>
        <tr>
        <td>www.atstravel.pt</td>
        <td>Licence RNVAT 8019</td>
        </tr>
        <tr>
        <td>VAT 514 974 567</td>
        <td></td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
            <td></td>
        </tr>
        @foreach($pedido->produtos as $p)
        @if($p->quartos()->count() > 0)
        <tr>
            <td>Hotel: {{$p->produto->nome}}</td>
            <td>Customer: {{$pedido->lead_name}}</td>
        </tr>

        @foreach($p->quartos()->get() as $q)
        <tr>

        </tr>
        <tr>
            <td>C.In: {{ $q->checkin }}</td>
            <td>C. Out: {{ $q->checkout }}</td>
            <td>Rooms: {{ $q->rooms }}</td>
        </tr>
        <tr>
            <td>Room Type: {{ $q->type }}</td>
            <td>Board: {{ $q->plan }}</td>
            <td>N Pax: {{ $q->people }}</td>
        </tr>
        @if($q->remark !== null)
        <tr>
            <td>Remarks: </td>
        </tr>
        @endif
        <tr>
        </tr>

        @if($q->pedidoquartoroom()->count() > 0)
        @foreach($q->pedidoquartoroom()->get() as $i => $room)
        @if($room->pedidoquartoroomname()->count() > 0)
        @foreach($room->pedidoquartoroomname()->get() as $roomname)
        <tr>
            <td>{{'Quarto '.($i + 1) }}</td>
            <td>{{$roomname->name}}</td>
        </tr>
        @endforeach
        @endif
        @endforeach
        @endif

        @endforeach
        @endif
        @endforeach
    </tbody>
</table>

<table>
    <tbody>
        <tr>
            <td>TOPICOS E DESCOBERTAS LDA</td>
            <td>Telef.(+351) 917 250 405</td>
        </tr>
        <tr>
            <td>Rua D. Carlos I,</td>
            <td>Mobile.(+351) 912 032 695</td>
        </tr>
        <tr>
            <td>Edíficio Perola do Arade, 53C - 1ºC </td>
            <td>reservations: incoming@atravel.pt</td>
        </tr>
        <tr>
            <td>8500-607 Portimão</td>
            <td>www.atstravel.pt</td>
        </tr>
        <tr>
            <td>Licence RNVAT 8019</td </tr> <tr>
            <td>VAT 514 974 567</td>
        </tr>

        <tr>
            <!-- pula linha no excel -->
        </tr>


        @foreach($pedido->produtos as $p)
        @if($p->quartos()->count() > 0)
        <tr>
            <td><b>Hotel:</b> {{$p->produto->nome}}</td>
            <td><b>Customer:</b> {{$pedido->lead_name}}</td>
        </tr>

        @foreach($p->quartos()->get() as $q)

        <tr>
            <!-- pula linha no excel -->
        </tr>

        <tr>
            <td><b>C.In</b>: {{ $q->checkin }}</td>
            <td><b>C. Out</b>: {{ $q->checkout }}</td>
            <td><b>Rooms</b>: {{ $q->rooms }}</td>
        </tr>
        <tr>
            <td><b>Room Type:</b> {{ $q->type }}</td>
            <td><b>Board</b>: {{ $q->plan }}</td>
            <td><b>N Pax</b>: {{ $q->people }}</td>
        </tr>

        @if($q->remark !== null)
        <tr>
            <td><b>Remarks:</b> {!! $q->remark !!}</td>
        </tr>
        @endif
        <tr>
            <!-- pula linha no excel -->
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
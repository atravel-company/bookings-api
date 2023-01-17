<div  class="w3-row">

<div class="w3-col l11" style="overflow-y: auto;">
<table class="w3-table w3-striped w3-centered">
                                        <tr style="background-color: #24AEC9; color: white;  ">
                                            <th style="background-color: white; color: white;  "></th>
                                            @foreach($period as $dt)
                                            @if(date('F Y', time())==date('F Y', strtotime($dt['month'])))
                                              <th style="white-space: nowrap; background-color: #F5AA3B; color: white;">{{ date('F Y', strtotime($dt['month']))}}</th>
                                            @else
                                              <th style="white-space: nowrap;">{{ date('F Y', strtotime($dt['month']))}}</th>
                                            @endif
                                            @endforeach
                                        </tr>
                                        
                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">BedNights</th>
                                           @foreach($period as $dt)
                                              <td>{{$dt['bednights']}}</td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">Room Nights</th>
                                           @foreach($period as $dt)
                                              <td>{{$dt['room_nights']}}</td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">Room Revenue</th>
                                           @foreach($period as $dt)
                                              <td>{{$dt['room_revenue']}}</td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">ADR</th>
                                           @foreach($period as $dt)
                                              <td>{{$dt['adr']}}</td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">F & B Revenue</th>
                                           @foreach($period as $dt)
                                              <td>{{$dt['extra']}}</td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: white; color: white; white-space: nowrap;">F & B</th>
                                           @foreach($period as $dt)
                                              <td></td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">Golf Revenue</th>
                                           @foreach($period as $dt)
                                              <td>{{$dt['golf_revenue']}}</td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">Golf Rentals Revenue</th>
                                           @foreach($period as $dt)
                                              <td>{{$dt['extraGolf']}}</td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: white; color: white; white-space: nowrap;">F & B</th>
                                           @foreach($period as $dt)
                                              <td></td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">Total Revenue</th>
                                           @foreach($period as $dt)
                                              <th>{{$dt['total_revenue']}}</th>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">cancellations</th>
                                           @foreach($period as $dt)
                                              <td>{{$dt['canceled']}}</td>
                                           @endforeach 
                                       </tr>

                                       <tr>
                                           <th style="background-color: #24AEC9; color: white; white-space: nowrap;">No Shows</th>
                                           @foreach($period as $dt)
                                               <td>{{$dt['another']}}</td>
                                           @endforeach 
                                       </tr>
                                                
                                        </table>
</div>
<div class="w3-col l1">&nbsp;</div>
</div>
                                        
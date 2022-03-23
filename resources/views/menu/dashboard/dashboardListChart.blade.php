<div class="three wide computer sixteen wide tablet sixteen wide mobile column">
  <div class="ui basic segment">
    <h4 class='ui mini header'>Pencapaian Revenue YTD Vs Total Budget</h4>
    <div id="spedemeterRevenueTahunan" class="centered"></div>
     @gaugechart('revenueTahunan','spedemeterRevenueTahunan')

    <h4 class='ui mini header'>Pencapaian Revenue YTD Vs Budget</h4>
    <div id="spedemeterRevenueBulanan"></div>
    @gaugechart('revenueBulanan','spedemeterRevenueBulanan')
  </div>
</div>

<div class="thirteen wide computer sixteen wide tablet sixteen wide mobile column">
  <div class="ui raises segment clearing">
    <div id="chartRevenue"></div>
    @columnchart('Votes','chartRevenue')

    <div class="ui grey ribbon label">Last Update: {{ $lastUpdate }}</div>
    <a  href="{{ route('sync data') }}" class="ui small button grey labeled icon right floated" id='updateRevenue'>
    	<i class="refresh icon"></i>
    Update Revenue</a>
  </div>
</div>

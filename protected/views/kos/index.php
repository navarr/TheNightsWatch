<?php

$lastType = null;

function statusToColor($status)
{
    $colors = array(
        KOS::STATUS_ACCEPTED => 'red',
        KOS::STATUS_DESERTER => 'darkred',
        KOS::STATUS_CAUTION => 'yellow',
        KOS::STATUS_WARNING => 'blue',
    );
    return $colors[$status];
}
?>
<p>
	Do you need to <em><a href="<?php echo $this->createUrl('kos/add'); ?>">file
			a report?</a> </em>
</p>
<table>
	<thead>
		<th colspan="2">Username</th>
		<th title="Year/month/day">Last Seen</th>
		<th>Server</th>
		<th>Latest Report</th>
	</thead>
	<tbody>
		<?php foreach($kos as $kosum): ?>
		<?php if($kosum->status != $lastType): ?>
		<tr>
			<th colspan="5"
				style="text-align: center; border-bottom: 1px solid white; padding-top: 10px;">
				<?php echo KOS::translateStatus($kosum->status); ?>
			</th>
		</tr>
		<?php $lastType = $kosum->status; endif; ?>
		<tr>
			<td style="padding:0;margin:0;width:5px;background-color:<?php echo statusToColor($kosum->status); ?>"></td>
			<td><a
				href="<?php echo $this->createUrl('kos/view',array('unique' => $kosum->ign)); ?>"><img
					src="<?php echo User::getHead($kosum->ign,16); ?>"
					style="width: 16px; height: 16px;" alt="" /> <?php echo CHtml::encode($kosum->ign); ?>
			</a>
			</td>

			<td><?php echo count($kosum->reports) ? $kosum->reports[0]->date->format("Y/n/j") : ''; ?>
			</td>
			<td><?php echo count($kosum->reports) ? CHtml::encode($kosum->reports[0]->server) : ''; ?>
			</td>
			<td><?php echo count($kosum->reports) ? (mb_strlen($kosum->reports[0]->report,'UTF-8') > 100 ? CHtml::encode(mb_substr($kosum->reports[0]->report,0,100))."..." : CHtml::encode($kosum->reports[0]->report)) : ''; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
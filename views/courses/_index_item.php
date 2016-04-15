<?php
/** @var \Mooc $plugin */
/** @var \Course $_index_item */
/** @var string[] $preview_images */

/** @var \Seminar_Perm $perm */
$perm = $GLOBALS['perm'];

$course = $_index_item;

if ($perm->have_studip_perm('autor', $course->id)) {
    $label = 'Zum Kurs';
    $params = array('cid' => $course->id);
} else {
    $label = 'Mehr…';
    $params = array('moocid' => $course->id);
}

$courseUrl = PluginEngine::getLink($plugin, $params, 'courses/show/'.$course->id);
?>
<article>
	<div class="course-avatar-wrapper">
  	    <img class="course-avatar-medium course-<?=$course->seminar_id?>" alt="<?=htmlReady($course->name)?>" title="<?=htmlReady($course->name)?>" src="<?= $preview_images[$course->id] ?: CourseAvatar::getAvatar($course->id)->getURL(CourseAvatar::MEDIUM) ?>" />
	</div>
  	

  <h1><?= htmlReady($course->name) ?></h1>
  <?
	$fields = DataFieldEntry::getDataFieldEntries($course->seminar_id);
	$value = $fields['25890c68a68d310baad033125b89f938']->value;
  ?>
  <p class=subtitle> Start: <?= htmlReady($value) ?></p>
  
  <?= \Studip\LinkButton::create($label, $courseUrl) ?>
</article>

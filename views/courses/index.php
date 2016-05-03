<?
$body_id = 'mooc-courses-index';
?>

<h1><?= _mooc("Alle Kurse") ?></h1>
<h2><?= _mooc("Registrieren Sie sich direkt f&uuml;r einen oder mehrere der aktuellen Kurse") ?></h2>

<section id=course-list>
  <?= $this->render_partial_collection('courses/_index_item', $courses) ?>
</section>

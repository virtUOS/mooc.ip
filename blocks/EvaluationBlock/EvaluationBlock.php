<?
namespace Mooc\UI\EvaluationBlock;

use Mooc\UI\Block;


/**
 * Display the course evaluations in a (M)ooc.IP block.
 *
 * @author André Klaßen <klassen@elan-ev.de>
 */
class EvaluationBlock extends Block
{
    const NAME = 'Evalutaionen';

    function initialize()
    {
        $this->defineField('evaluations', \Mooc\SCOPE_BLOCK, '');
    }

    public function author_view()
    {
        if (!$active = self::evaluationActivated()) {
            return compact('active');
        }

        return compact('active');
    }


    function student_view()
    {
        $this->setGrade(1.0);
        $eval_db = new \EvaluationDB();
        $evaluations = \StudipEvaluation::findMany($eval_db->getEvaluationIDs($this->container['cid'], EVAL_STATE_ACTIVE));
        $content = self::mustachify($evaluations);
        return array('evaluations' => $evaluations, 'content' => $content);
    }

    /**
     * {@inheritDoc}
     */
    public function isEditable()
    {
        return false;
    }


    private static function evaluationActivated()
    {
        return get_config('VOTE_ENABLE');
    }

    private static  function mustachify($evaluations) {
        $content = array();
        foreach($evaluations as $evaluation) {
            $content[] = array('id' =>  $evaluation->id ,
                               'title' => $evaluation->title,
                               'description' => $evaluation->text,
                               'link' => \Studip\LinkButton::create(_('Anzeigen'),
                                            \URLHelper::getURL('show_evaluation.php',
                                                array('evalID' => $evaluation->id)),
                                                    array('data-dialog' => '', 'target' => '_blank')
                                                )
                );
        }
        return $content;
    }
}

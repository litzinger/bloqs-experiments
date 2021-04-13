<?php

use BoldMinded\Bloqs\Model\Block;
use BoldMinded\Experiments\Services\Variation;

require PATH_THIRD . 'bloqs_experiments/addon.setup.php';

class Bloqs_experiments_ext
{
    public $version = BLOQS_EXPERIMENTS_VERSION;

    /**
     * @var Variation
     */
    private $variation;

    public function __construct()
    {
        $this->variation = ee('experiments:Variation');
        $this->variation->setOptions([], ee('Security/XSS')->clean($_GET));
    }

    /**
     * @param Block $block
     * @return bool
     */
    public function blocks_hide_block(Block $block)
    {
        $atomValue = $this->findExperimentAtomValue($block);

        if (!is_int($atomValue)) {
            return false;
        }

        if (!$this->variation->shouldShowContent($atomValue)) {
            return true;
        }

        return false;
    }

    /**
     * @param array $vars
     * @return int|null
     */
    private function findExperimentAtomValue(Block $block)
    {
        foreach ($block->getAtoms() as $atom) {
            if ($atom->getDefinition()->getType() === 'experiments') {
                return (int) $atom->getValue();
            }
        }

        return null;
    }

    /**
     * @return void
     */
    public function activate_extension()
    {
        $this->add_hooks([
            ['hook'=>'blocks_hide_block', 'method'=>'blocks_hide_block'],
        ]);
    }

    /**
     * @return void
     */
    function disable_extension()
    {
        ee()->db->where('class', __CLASS__);
        ee()->db->delete('extensions');
    }

    /**
     * @param string $current
     * @return bool
     */
    function update_extension($current = '')
    {
        if ($current === '' || $current === BLOQS_EXPERIMENTS_VERSION) {
            return false;
        }

        return false;
    }

    /**
     * @param array $hooks
     */
    private function add_hooks($hooks = [])
    {
        if (empty($hooks)) {
            return;
        }

        // Default
        $template = [
            'class'    => __CLASS__,
            'settings' => serialize([]),
            'priority' => 5,
            'version'  => BLOQS_EXPERIMENTS_VERSION,
            'enabled'  => 'y'
        ];

        foreach($hooks as $hook) {
            /** @var CI_DB_result $query */
            $query = ee()->db->get_where('extensions', [
                'hook' => $hook['hook'],
                'class' => __CLASS__
            ]);

            if($query->num_rows() == 0) {
                ee()->db->insert('extensions', array_merge($template, $hook));
            }
        }
    }
}

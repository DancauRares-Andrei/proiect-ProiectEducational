<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Registry\Registry;

$list  = $displayData['list'];
$pages = $list['pages'];
$total = $list['total'];

$options = new Registry($displayData['options']);

$showLimitBox     = $options->get('showLimitBox', false);
$showPagesLinks   = $options->get('showPagesLinks', true);
$showLimitStart   = $options->get('showLimitStart', true);
$showItemPosition = $options->get('showItemPosition', true);

// Calculate to display range of pages
$currentPage = 1;
$range       = 1;
$step        = 5;

if (!empty($pages['pages'])) {
    foreach ($pages['pages'] as $k => $page) {
        if (!$page['active']) {
            $currentPage = $k;
        }
    }
}

if ($currentPage >= $step) {
    if ($currentPage % $step === 0) {
        $range = ceil($currentPage / $step) + 1;
    } else {
        $range = ceil($currentPage / $step);
    }
}
$first = ($currentPage - 1) * $list['limit'] + 1;
$last  = $first + $list['limit'] - 1;
$last  = $last > $total ? $total : $last;

?>
<?php if (!empty($pages) || $showItemPosition) : ?>
    <nav class="pagination__wrapper" aria-label="<?php echo Text::_('JLIB_HTML_PAGINATION'); ?>">
        <?php if ($showItemPosition) : ?>
            <div class="text-end me-3">
                <?php echo Text::sprintf('JLIB_HTML_PAGINATION_NUMBERS', $first, $last, $total); ?>
            </div>
        <?php endif; ?>

        <div class="pagination pagination-toolbar text-center mt-0">

            <?php if ($showLimitBox) : ?>
                <div class="limit float-end">
                    <?php echo Text::_('JGLOBAL_DISPLAY_NUM') . $list['limitfield']; ?>
                </div>
            <?php endif; ?>

            <?php if ($showPagesLinks && !empty($pages)) : ?>
                <ul class="pagination ms-auto me-0">
                    <?php echo LayoutHelper::render('joomla.pagination.link', $pages['start']); ?>
                    <?php echo LayoutHelper::render('joomla.pagination.link', $pages['previous']); ?>
                    <?php foreach ($pages['pages'] as $k => $page) : ?>
                        <?php $output = LayoutHelper::render('joomla.pagination.link', $page); ?>
                        <?php if (in_array($k, range($range * $step - ($step + 1), $range * $step), true)) : ?>
                            <?php if (($k % $step === 0 || $k === $range * $step - ($step + 1)) && $k !== $currentPage && $k !== $range * $step - $step) : ?>
                                <?php $output = preg_replace('#(<a.*?>).*?(</a>)#', '$1...$2', $output); ?>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php echo $output; ?>
                    <?php endforeach; ?>
                    <?php echo LayoutHelper::render('joomla.pagination.link', $pages['next']); ?>
                    <?php echo LayoutHelper::render('joomla.pagination.link', $pages['end']); ?>
                </ul>
            <?php endif; ?>

            <?php if ($showLimitStart) : ?>
                <input type="hidden" name="<?php echo $list['prefix']; ?>limitstart" value="<?php echo $list['limitstart']; ?>">
            <?php endif; ?>

        </div>
    </nav>
<?php endif; ?>

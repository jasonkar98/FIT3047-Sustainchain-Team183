<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Enquiry $enquiry
 */
?>
<div class="row">
    <div class="column column-80">
        <div class="enquiries form content">
            <?= $this->Form->create($enquiry) ?>
            <fieldset>
                <legend><?= __('Add Enquiry') ?></legend>
                <?php
                    echo $this->Form->control('full_name', ['label' => 'Full Name']);
                    echo $this->Form->control('email', ['label' => 'Email Address']);
//                    echo $this->Form->control('date');
                    echo $this->Form->control('subject', ['label' => 'Enquiry Title']);
                    echo $this->Form->control('body', ['label' => 'What Is Your Enquiry?']);
//                    echo $this->Form->control('email_sent');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Send Enquiry')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

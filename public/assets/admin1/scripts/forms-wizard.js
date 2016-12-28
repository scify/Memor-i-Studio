var FormsWizard = {

	manageFormElements: function (tab, navigation, index) {
		var countSteps = tab.parents('.panel:first').find('.nav-wizard li').length;
		var currentStep = index + 1;

		if(countSteps === currentStep) {
			tab.parents('.panel:first').find('.bs-wizard-next').hide();
			tab.parents('.panel:first').find('.bs-wizard-submit').show();
		} else {
			tab.parents('.panel:first').find('.bs-wizard-next').show();
			tab.parents('.panel:first').find('.bs-wizard-submit').hide();
		}

		if(currentStep === 1)
			tab.parents('.panel:first').find('.bs-wizard-prev').hide();
		else
			tab.parents('.panel:first').find('.bs-wizard-prev').show();
	},

	getRules: function () {
		var rules = {
			email: {
				minlength: 5,
				required: true,
				email: true
			},
			password: {
				minlength: 5,
				required: true
			},
			rpassword: {
				minlength: 5,
				required: true
				//equalTo: '#mirror-password'
			},
			facebook: {
				required: true,
				minlength: 3
			},
			twitter: {
				required: true,
				minlength: 3
			},
			address: {
				required: true,
				minlength: 3
			}
		}
		return rules;
	},

	wizardProgress: function () {
		var $form1_validator = $('#form1').validate({
			rules: FormsWizard.getRules()
		});

		var $bsWizardProgress = $('.bs-wizard-progress');
		$bsWizardProgress.bootstrapWizard({
			/*
			'tabClass': 'nav nav-pills',
			'nextSelector': '',
			'previousSelector': '',
			'firstSelector': '',
			'lastSelector': '',
			'onInit': '',
			'onShow': '',
			'onNext': '',
			'onPrevious': '',
			'onFirst': '',
			'onLast': '',
			'onTabClick': '',
			'onTabShow':'',
			*/
			'tabClass': 'nav-wizard',
			'nextSelector': $bsWizardProgress.find('.bs-wizard-next'),
			'previousSelector': $bsWizardProgress.find('.bs-wizard-prev'),
			'onNext': function(tab, navigation, index) {
				var $valid = $bsWizardProgress.find('form:first').valid();
				if(!$valid) {
					$form1_validator.focusInvalid();
					return false;
				}
				FormsWizard.manageFormElements(tab, navigation, index);
			},
			'onTabShow': function(tab, navigation, index) {
				var $total = navigation.find('li').length;
				var $current = index+1;
				var $percent = ($current/$total) * 100;
				$bsWizardProgress.find('.progress-bar').css({ 'width': $percent+'%' });
				FormsWizard.manageFormElements(tab, navigation, index);
			}
		});

		var focusInForm1 = false;

		$('#form1').focusin(function () {
			focusInForm1 = true;
		});
		$('#form1').focusout(function () {
			focusInForm1 = false;
		});

		$(window).keydown(function(event){
			if( focusInForm1 && event.keyCode == 13 && !$('#form1').valid()) {
				event.preventDefault();
				return false;
			}
		});
	},

	wizardSteps: function () {
		var $form2_validator = $('#form2').validate({
			rules: FormsWizard.getRules()
		});

		var $bsWizardSteps = $('.bs-wizard-steps');
		$bsWizardSteps.bootstrapWizard({
			'tabClass': 'nav-wizard',
			'nextSelector': $bsWizardSteps.find('.bs-wizard-next'),
			'previousSelector': $bsWizardSteps.find('.bs-wizard-prev'),
			'onNext': function(tab, navigation, index) {
				var $valid = $bsWizardSteps.find('form:first').valid();
				if(!$valid) {
					$form2_validator.focusInvalid();
					return false;
				}
				tab.next().children().removeClass('btn-white');
				FormsWizard.manageFormElements(tab, navigation, index);
			},
			'onPrevious': function(tab, navigation, index) {
				tab.children().removeClass('btn-primary');
				tab.prev().children().removeClass('btn-success').addClass('btn-primary');
			},
			'onTabClick': function (tab, navigation, index, clickedIndex) {
				if(index<clickedIndex)
					return false;
				tab.children().removeClass('btn-primary');
				tab.prev().children().removeClass('btn-success').addClass('btn-primary');
			},
			onTabShow: function(tab, navigation, index) {
				tab.prev().children().removeClass('btn-primary').addClass('btn-success');
				tab.children().addClass('btn-primary');
				FormsWizard.manageFormElements(tab, navigation, index);
			}
		});
	},

	wizardStepsWithProgress: function () {
		var $form3_validator = $('#form3').validate({
			rules: FormsWizard.getRules()
		});

		var $bsWizardStepsWithProgress = $('.bs-wizard-steps-with-progress');
		$bsWizardStepsWithProgress.bootstrapWizard({
			'tabClass': 'nav-wizard',
			'nextSelector': $bsWizardStepsWithProgress.find('.bs-wizard-next'),
			'previousSelector': $bsWizardStepsWithProgress.find('.bs-wizard-prev'),
			'onNext': function(tab, navigation, index) {
				var $valid = $bsWizardStepsWithProgress.find('form:first').valid();
				if(!$valid) {
					$form3_validator.focusInvalid();
					return false;
				}
				tab.next().children().removeClass('btn-white');
				FormsWizard.manageFormElements(tab, navigation, index);
			},
			'onPrevious': function(tab, navigation, index) {
				tab.children().removeClass('btn-primary');
				tab.prev().children().removeClass('btn-success').addClass('btn-primary');
			},
			'onTabClick': function (tab, navigation, index, clickedIndex) {
				if(index<clickedIndex)
					return false;
				tab.children().removeClass('btn-primary');
				tab.prev().children().removeClass('btn-success').addClass('btn-primary');
			},
			onTabShow: function(tab, navigation, index) {
				tab.prev().children().removeClass('btn-primary').addClass('btn-success');
				tab.children().addClass('btn-primary');
				tab.parents('.panel:first').find('.panel-title').html( tab.data('title') );

				var $total = navigation.find('li').length;
				var $current = index+1;
				var $percent = ($current/$total) * 100;

				if($current != $total)
					$percent = $percent-(100/($total*2));

				$bsWizardStepsWithProgress.find('.progress-bar').css({ width: $percent+'%' });

				FormsWizard.manageFormElements(tab, navigation, index);
			}
		});
	},

	wizardJustified: function () {
		var $form4_validator = $('#form4').validate({
			rules: FormsWizard.getRules()
		});

		var $bsWizardJustified = $('.bs-wizard-justified');
		$bsWizardJustified.bootstrapWizard({
			'tabClass': 'nav-wizard',
			'nextSelector': $bsWizardJustified.find('.bs-wizard-next'),
			'previousSelector': $bsWizardJustified.find('.bs-wizard-prev'),
			'onNext': function(tab, navigation, index) {
				var $valid = $bsWizardJustified.find('form:first').valid();
				if(!$valid) {
					$form4_validator.focusInvalid();
					return false;
				}
				FormsWizard.manageFormElements(tab, navigation, index);
			},
			'onTabClick': function (tab, navigation, index, clickedIndex) {
				if(index<clickedIndex)
					return false;
			},
			onTabShow: function(tab, navigation, index) {
				FormsWizard.manageFormElements(tab, navigation, index);
			}
		});
	},

	init: function () {
		this.wizardProgress();
		this.wizardSteps();
		this.wizardStepsWithProgress();
		this.wizardJustified();
	}
}





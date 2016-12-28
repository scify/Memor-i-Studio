var SoundPlayer = {

	loadSounds: function () {
		ion.sound({
			sounds: [
				{name: 'beer_can_opening'},
				{name: 'bell_ring'},
				{name: 'branch_break'},
				{name: 'button_click'},
				{name: 'button_click_on'},
				{name: 'button_push'},
				{name: 'button_tiny'},
				{name: 'camera_flashing'},
				{name: 'camera_flashing_2'},
				{name: 'cd_tray'},
				{name: 'computer_error'},
				{name: 'door_bell'},
				{name: 'door_bump'},
				{name: 'glass'},
				{name: 'keyboard_desk'},
				{name: 'light_bulb_breaking'},
				{name: 'metal_plate'},
				{name: 'metal_plate_2'},
				{name: 'pop_cork'},
				{name: 'snap'},
				{name: 'staple_gun'},
				{name: 'tap'},
				{name: 'water_droplet'},
				{name: 'water_droplet_2'},
				{name: 'water_droplet_3'}
			],
			volume: 1,
			path: '../../assets/globals/plugins/ionsound/sounds/',
			preload: false
		});
	},

	listenSoundEvents: function () {
		$('.ionsound-beer-can').click(function () {
			ion.sound.play('beer_can_opening');
		});

		$('.ionsound-bell-ring').click(function () {
			ion.sound.play('bell_ring');
		});

		$('.ionsound-branch-break').click(function () {
			ion.sound.play('branch_break');
		});

		$('.ionsound-button-click').click(function () {
			ion.sound.play('button_click');
		});

		$('.ionsound-button-click-on').click(function () {
			ion.sound.play('button_click_on');
		});

		$('.ionsound-button-push').click(function () {
			ion.sound.play('button_push');
		});

		$('.ionsound-button-tiny').click(function () {
			ion.sound.play('button_tiny');
		});

		$('.ionsound-camera-flashing').click(function () {
			ion.sound.play('camera_flashing');
		});

		$('.ionsound-camera-flashing-2').click(function () {
			ion.sound.play('camera_flashing_2');
		});

		$('.ionsound-cd-tray').click(function () {
			ion.sound.play('cd_tray');
		});

		$('.ionsound-computer-error').click(function () {
			ion.sound.play('computer_error');
		});

		$('.ionsound-door-bell').click(function () {
			ion.sound.play('door_bell');
		});

		$('.ionsound-door-bump').click(function () {
			ion.sound.play('door_bump');
		});

		$('.ionsound-glass').click(function () {
			ion.sound.play('glass');
		});

		$('.ionsound-keyboard-desk').click(function () {
			ion.sound.play('keyboard_desk');
		});

		$('.ionsound-light-bulb').click(function () {
			ion.sound.play('light_bulb_breaking');
		});

		$('.ionsound-metal-plate').click(function () {
			ion.sound.play('metal_plate');
		});

		$('.ionsound-metal-plate-2').click(function () {
			ion.sound.play('metal_plate_2');
		});

		$('.ionsound-pop-cork').click(function () {
			ion.sound.play('pop_cork');
		});

		$('.ionsound-snap').click(function () {
			ion.sound.play('snap');
		});

		$('.ionsound-staple-gun').click(function () {
			ion.sound.play('staple_gun');
		});

		$('.ionsound-tap').click(function () {
			ion.sound.play('tap');
		});

		$('.ionsound-water-droplet').click(function () {
			ion.sound.play('water_droplet');
		});

		$('.ionsound-water-droplet-2').click(function () {
			ion.sound.play('water_droplet_2');
		});

		$('.ionsound-water-droplet-3').click(function () {
			ion.sound.play('water_droplet_3');
		});
	},

	init: function () {
		this.loadSounds();
		this.listenSoundEvents();
	}
}


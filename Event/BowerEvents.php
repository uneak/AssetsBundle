<?php

	namespace Uneak\AssetsBundle\Event;

	final class BowerEvents {
		const PRE_EXEC = 'bower.pre_exec';
		const POST_EXEC = 'bower.post_exec';
		const PRE_INSTALL = 'bower.pre_install';
		const POST_INSTALL = 'bower.post_install';
		const PRE_UPDATE = 'bower.pre_update';
		const POST_UPDATE = 'bower.post_update';
		const PRE_LIST = 'bower.pre_list';
		const POST_LIST = 'bower.post_list';
	}

<?php

	namespace Uneak\AssetsBundle\Event;

	final class NpmEvents {
		const PRE_EXEC = 'npm.pre_exec';
		const POST_EXEC = 'npm.post_exec';
		const PRE_INSTALL = 'npm.pre_install';
		const POST_INSTALL = 'npm.post_install';
	}

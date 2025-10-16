<div class="copyright">
		<?php $footer = $settingWeb->footer_login ? str_replace('{{YEAR}}', date('Y'), $settingWeb->footer_login) : '';
		echo $footer;
		?>
	</div>
	</div><!-- login container -->
</body>
</html>
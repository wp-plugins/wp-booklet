<?php

class WP_Booklet2_Command {

	protected $_command;
	protected $_modifiers;

	/** 
	 * The constructor
	 * 
	 * @param $command string - the command
	 * @param $modifiers string - command arguments/options
	 *
	 * @return void
	 */
	function __construct( $command, $modifiers) {

		$this->_command = $command;
		$this->_modifiers = $modifiers;

		if ( !$this->_command_exists() ) {
			throw new Exception("Command or program doesn't exist");
		}

	}

	/** 
	 * Checks existence of the command 
	 *
	 */
	protected function _command_exists() {
		$existence = exec( $this->_command ." ". $this->_modifiers . " 2>&1", $output, $return_var );

		return !($return_var > 0 );
	}

	/**
	 * Run an external command or program
	 *
	 * @return array - 	An array that contains 'error' status and 'message'
	 */
	public function run_command() {
		try {
			exec( $this->_command . " " . $this->_modifiers . " 2>&1", $output, $return_var );
		
			if ( $return_var === 0  ) {
				$result['error'] = false;
				$result['message'] = implode(" ",$output);
			}
			else {
				$result['error'] = true;
				$result['message'] = "Command exited with error code: " . $return_var;
			}
		}
		catch(Exception $e) {
			$result['error'] = true;
			$result['message'] = $e->getMessage();
		}
		
		return $result;
	}

}
<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * sfAdvanced1_Sniffs_NamingConventions_VariableSubstitutionInStringSniff
 *
 * @package    SfAdvanced
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class sfAdvanced1_Sniffs_NamingConventions_VariableSubstitutionInStringSniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{
  protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    return null;
  }

  protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    return null;
  }

  protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
  {
    $tokens = $phpcsFile->getTokens();
    if (T_DOUBLE_QUOTED_STRING !== $tokens[$stackPtr]['code'])
    {
      return null;
    }

    $error = 'Variable substitution in strings is not permitted. Use string-concatenation or the sprintf() function instead.';
    $phpcsFile->addError($error, $stackPtr);
  }
}

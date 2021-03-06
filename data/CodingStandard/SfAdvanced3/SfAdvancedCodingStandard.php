<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

if (class_exists('PHP_CodeSniffer_Standards_CodingStandard', true) === false)
{
  throw new PHP_CodeSniffer_Exception('Class PHP_CodeSniffer_Standards_CodingStandard not found');
}

/**
 * sfAdvanced1 Coding Standard
 *
 * @package    SfAdvanced
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PHP_CodeSniffer_Standards_sfAdvanced1_sfAdvanced1CodingStandard extends PHP_CodeSniffer_Standards_CodingStandard
{
  public function getIncludedSniffs()
  {
    return array(
      'Zend',
      'Generic/Sniffs/ControlStructures/InlineControlStructureSniff.php',
      'Generic/Sniffs/NamingConventions/UpperCaseConstantNameSniff.php',
      'Squiz/Sniffs/Scope/MemberVarScopeSniff.php',
      'Squiz/Sniffs/Scope/MethodScopeSniff.php',
      'Squiz/Sniffs/Strings/DoubleQuoteUsageSniff.php',
    );
  }

  public function getExcludedSniffs()
  {
    return array(
      'PEAR/Sniffs/ControlStructures/ControlSignatureSniff.php',
      'PEAR/Sniffs/WhiteSpace/ScopeClosingBraceSniff.php',
      'Zend/Sniffs/NamingConventions/ValidVariableNameSniff.php',
      'Squiz/Sniffs/Functions/GlobalFunctionSniff.php',
    );
  }
}

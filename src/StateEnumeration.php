<?php namespace DCarbone\CurlPlus;

/**
 * Class StateEnumeration
 * @package DCarbone\CurlPlus
 */
abstract class StateEnumeration
{
    const STATE_NEW         = 0;
    const STATE_INITIALIZED = 1;
    const STATE_EXECUTING   = 2;
    const STATE_EXECUTED    = 3;
    const STATE_CLOSED      = 4;
}
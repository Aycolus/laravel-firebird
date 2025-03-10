<?php

namespace Aycolus\Firebird;

use Aycolus\Firebird\Query\Builder as FirebirdQueryBuilder;
use Aycolus\Firebird\Query\Grammars\FirebirdGrammar as FirebirdQueryGrammar;
use Aycolus\Firebird\Query\Processors\FirebirdProcessor as FirebirdQueryProcessor;
use Aycolus\Firebird\Schema\FirebirdBuilder as FirebirdSchemaBuilder;
use Aycolus\Firebird\Schema\Grammars\FirebirdGrammar as FirebirdSchemaGrammar;
use Illuminate\Database\Connection as DatabaseConnection;

class FirebirdConnection extends DatabaseConnection
{
    /**
     * Get the default query grammar instance.
     *
     * @return \Illuminate\Database\Query\Grammars\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        return new FirebirdQueryGrammar;
    }

    /**
     * Get the default post processor instance.
     *
     * @return \Illuminate\Database\Query\Processors\Processor
     */
    protected function getDefaultPostProcessor()
    {
        return new FirebirdQueryProcessor;
    }

    /**
     * Get a schema builder instance for this connection.
     *
     * @return \Firebird\Schema\FirebirdBuilder
     */
    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new FirebirdSchemaBuilder($this);
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return \Firebird\Schema\Grammars\FirebirdGrammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new FirebirdSchemaGrammar);
    }

    /**
     * Get a new query builder instance.
     *
     * @return \Firebird\Query\Builder
     */
    public function query()
    {
        return new FirebirdQueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }

    /**
     * Execute a stored procedure.
     *
     * @param  string  $procedure
     * @param  array  $values
     * @return \Illuminate\Support\Collection
     */
    public function executeProcedure($procedure, array $values = [])
    {
        return $this->query()->fromProcedure($procedure, $values)->get();
    }
}

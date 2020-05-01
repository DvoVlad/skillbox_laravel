<?php

namespace App\Service;

class DataUpdater
{
	public function update($data, $v)
	{
		$data->update($v);
		$data->tags()->detach();
	}
}

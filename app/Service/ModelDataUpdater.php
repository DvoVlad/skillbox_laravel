<?php

namespace App\Service;

class ModelDataUpdater
{
	public function update($data, $v)
	{
		$data->update($v);
		$data->tags()->detach();
	}
}

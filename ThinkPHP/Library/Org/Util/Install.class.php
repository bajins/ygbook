<?php
namespace Org\Util;
class Install {
	public $sqlcharset = 'utf8';
	public function check_writeable($file) {
		if (file_exists($file)) {
			if (is_dir($file)) {
				$dir = $file;
				if ($fp = @fopen("$dir/test.txt", 'w')) {
					@fclose($fp);
					@unlink("$dir/test.txt");
					$writeable = 1;
				} else {
					$writeable = 0;
				}
			} else {
				if ($fp = @fopen($file, 'a+')) {
					@fclose($fp);
					$writeable = 1;
				} else {
					$writeable = 0;
				}
			}
		} else {
			$writeable = 2;
		}
		return $writeable;
	}

	public function sql_execute($sql, $link) {
		$sqls = $this->sql_split($sql, $link);
		if (is_array($sqls)) {
			foreach ($sqls as $sql) {
				if (trim($sql) != '') {
					$res = mysqli_query($link, $sql);
				}
			}
		} else {
			mysqli_query($link, $sqls);
		}
		return true;
	}
	/**
	 * 数据分离
	 */
	public function sql_split($sql, $link) {
		if ($this->version($link) > '4.1' && $this->sqlcharset) {
			$sql = preg_replace("/TYPE=(InnoDB|MyISAM)( DEFAULT CHARSET=[^; ]+)?/", "TYPE=\\1 DEFAULT CHARSET=" . $this->sqlcharset, $sql);
		}
		
		$sql = str_replace("\r", "\n", $sql);
		$ret = array ();
		$num = 0;
		$queriesarray = explode(";\n", trim($sql));
		unset($sql);
		foreach ($queriesarray as $query) {
			$ret[$num] = '';
			$queries = explode("\n", trim($query));
			$queries = array_filter($queries);
			foreach ($queries as $query) {
				$str1 = substr($query, 0, 1);
				if ($str1 != '#' && $str1 != '-')
					$ret[$num] .= $query;
			}
			$num++;
		}
		return ($ret);
	}

	/**
	 * 返回 MySQL 服务器的信息
	 */
	public function version($link) {
		if (empty($this->version)) {
			$this->version = mysqli_get_server_info($link);
		}
		return $this->version;
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Menu extends CI_Model{

	private $tabel='t_menu_akses';

	private function get_menu($data, $parent = 0, $arrMenu) {
		static $i = 1;
		if (isset($data[$parent])) {
			
			if ($i == 1): $class = "class=\"sidebar-menu\"";
			else: $class = "class=\"treeview-menu\"";
			endif;
			$html = "<ul " . $class . ">";
			$i++;
			foreach ($data[$parent] as $v) {
				if (in_array($v->id_menu_akses, explode(',', $arrMenu)) && $v->dipilih == 1):
				//if ( $v->dipilih == 1 ):
					$child = $this->get_menu($data, $v->id_menu_akses, $arrMenu);
					if ($v->sub == 1):
						$html .= "<li class=\"treeview\"><a href=\"#\"><i class=\"".$v->ikon."\"></i><span>" . $v->menu . "</span><i class=\"fa fa-angle-left pull-right\"></i></a>";                   
					else:
					$html .= "<li><a href=\"" . base_url() . $v->module . "\" ><i class=\"".$v->ikon."\"></i>" . $v->menu . "</a>";
					endif;
					if ($child) {
						$i--;
						$html .= $child;
					}
					$html .= '</li>';
				elseif ($v->dipilih == 0):
					$child = $this->get_menu($data, $v->id_menu_akses, $arrMenu);
					if ($v->sub == 1):
						$html .= "<li class=\"treeview\"><a href=\"#\"><i class=\"".$v->ikon."\"></i><span>" . $v->menu . "</span><i class=\"fa fa-angle-left pull-right\"></i></a>";                   
					else:
					$html .= "<li><a href=\"" . base_url() . $v->module . "\" ><i class=\"".$v->ikon."\"></i>" . $v->menu . "</a>";
					endif;
					if ($child) {
						$i--;
						$html .= $child;
					}
					$html .= '</li>';
				endif;
			}
			$html .= "\n</ul>";
			return $html;
		} else {
			return false;
		}
	}
	
	private function daftarmenupilihan($data, $parent = 0, $arrMenu)
	{
		static $i = 1;
		if (isset($data[$parent])) {
			
			$html = "<ul>";
			$i++;
			foreach ($data[$parent] as $v) {
				if ( $v->dipilih == 1 ):
				$child = $this->daftarmenupilihan($data, $v->id_menu_akses, $arrMenu);
				$html .= "<li><input type=\"checkbox\" name=\"chkmenu[]\" id=\"chk_". $v->id_menu_akses ."\" value=\"". $v->id_menu_akses ."\"><span>" . $v->menu . "</span>";
				if ($child) {
					$i--;
					$html .= $child;
				}
				$html .= '</li>'; elseif ($v->dipilih == 0):
				$html .= "<li><input type=\"checkbox\" name=\"chkmenu[]\" id=\"chk_". $v->id_menu_akses ."\" value=\"". $v->id_menu_akses ."\"><span>" . $v->menu . "</span></li>";
				endif;
			}
			$html .= "</ul>";
			return $html;
		} else {
			return false;
		}
	}
	
	private function getAll(){
		$data = array();
		$query = $this->db->query("select * from ". $this->tabel ." where aktif=1");
		foreach ($query->result() as $row):
			$data[$row->id_parent][] = $row;
        endforeach;
		return $data;
	}
	
	private function getMenuYgHanyaBisaDipilih(){
		$data = array();
		$query = $this->db->query("select * from ". $this->tabel ." where dipilih=1 and aktif=1");
		foreach ($query->result() as $row):
			$data[$row->id_parent][] = $row;
        endforeach;
		return $data;
	}

	private function getMenuYgHanyaBisaDipilihDaerah(){
		$data = array();
		$query = $this->db->query("select * from ". $this->tabel ." where dipilih=1 and aktif=1 and daerah=1");
		foreach ($query->result() as $row):
			$data[$row->id_parent][] = $row;
        endforeach;
		return $data;
	}
	
	private function getMenuAccess(){
		$this->db->where('id_user', $this->session->userdata('id_user'));
		$query = $this->db->get('t_users');
		return $query->row()->menuakses;
	}
	
	public function ShowListMenu(){
		return $this->get_menu($this->getAll(), 0, $this->getMenuAccess());
	}
	
	public function ShowPilihanMenu(){
		return $this->daftarmenupilihan($this->getMenuYgHanyaBisaDipilih(), 0, $this->getMenuYgHanyaBisaDipilih());
	}

	public function ShowPilihanMenuDaerah(){
		return $this->daftarmenupilihan($this->getMenuYgHanyaBisaDipilihDaerah(), 0, $this->getMenuYgHanyaBisaDipilihDaerah());
	}

}
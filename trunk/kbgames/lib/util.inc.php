<?php
//--------------------------------------------------------------------------------------------------------------------//
// Interface: Iterator
//--------------------------------------------------------------------------------------------------------------------//
interface VectorIterator{
	public function hasNext();
	public function next();
	public function remove();
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: Vector
//--------------------------------------------------------------------------------------------------------------------//
class Vector{
	var $array;
	var $count;

	public function Vector(){
		$this->array = array();
		$this->count = 0;
	}

	public function size(){
		return count($this->array);
	}

	public function isEmpty(){
		return ($this->size() == 0);
	}

	public function addElement($element){
		$this->array[] = $element;
	}

	public function elementAt($index){
		return $this->array[$index];
	}

	public function contains($element){
		return ($this->indexOf($element,0) >= 0);
	}

	public function indexOf($element, $index = 0){
		for ($i = 0; $i < $this->size(); $i++){
			
			$tmp = $this->array[$i];
			if($tmp == $element){
				return $i;
			}
		}
		return -1;
	}

	public function removeElement($element){
		$i = $this->indexOf($element);
		if ($i >= 0) {
			$this->array[$i] = NULL;
			return true;
		}
		return false;
	}

	public function iterator(){
		$iter = new IteratorImpl($this->array);
		return $iter;
	}

	public function toArray(){
		return $this->array;
	}

	public function add($element){
		$this->addElement($element);
	}

	public function get($index){
		return $this->elementAt($index);
	}

	public function remove($index){
		if (isset($this->array[$index])){
			unset($this->array[$index]);
		}
	}

	public function replace($index, $element){
		$this->remove($index);
		$this->array[$index] = $element;
	}

}

//--------------------------------------------------------------------------------------------------------------------//
// Class: IteratorImpl
//--------------------------------------------------------------------------------------------------------------------//
class IteratorImpl implements VectorIterator {
	private $cursor = 0;
	private $last = -1;
	private $array;

	public function IteratorImpl($array){
		$this->array = $array;
	}

	public function hasNext(){
		return ($cursor != count($this->array));
	}

	public function next(){
		$element = $this->array[$this->cursor];
		$this->last = $this->cursor++;
		return $element;
	}

	public function remove(){
		if($this->last != -1){
			$this->array[$this->last] = NULL;
			if ($this->last < $this->cursor) {
				$this->cursor--;
			}
		}
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: HashMap
//--------------------------------------------------------------------------------------------------------------------//
class HashMap{
	private $map;

	public function HashMap(){
		$this->clear();
	}

	public function size(){
		return count($this->map);
	}

	public function isEmpty(){
		return ($this->size() == 0);
	}

	public function get($key){
		if (isset($this->map[$key])) {
			return $this->map[$key];
		}
		return NULL;
	}

	public function put($key, $value){
		$this->remove($key);
		$this->map[$key] = $value;
	}

	public function remove($key){
		if ($this->get($key) != NULL) {
			unset($this->map[$key]);
		}
	}

	public function clear(){
		$this->map = array();
	}

	public function keySet(){
		return new SetImpl(array_keys($this->map));
	}
}

//--------------------------------------------------------------------------------------------------------------------//
// Interface: Set
//--------------------------------------------------------------------------------------------------------------------//
interface Set{
	public function iterator();
}

//--------------------------------------------------------------------------------------------------------------------//
// Class: SetImpl
//--------------------------------------------------------------------------------------------------------------------//
class SetImpl implements Set{

	private $arr;

	public function Set($array){
		$this->arr = $array;
	}

	public function iterator(){
		return new IteratorImpl($this->arr);
	}
}

class StringFunctions{
	public static function startsWith($str, $prefix){
		if (($str == NULL) || ($prefix == NULL)) {
			return false;
		}
		$sub = substr($str,0, strlen($prefix));
		return ($prefix == $sub);
	}
}
?>
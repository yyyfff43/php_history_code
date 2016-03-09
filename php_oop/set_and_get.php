<?php
header("Content-type: text/html; charset=utf-8");
//PHP类中__get和__set方法的使用;
//在PHP5 中，如果变量是private属性,预定义了两个函数“__get()”和“__set()”来获取和赋值其属性,以及检查属性的“__isset()”和删除属性的方法“__unset()”,使用时需预先在程序中声明。

class Person
{
	//下面是人的成员属性， 都是封装的私有成员
		private $name; //人的名字
		private $sex; //人的性别
		private $age; //人的年龄
	//__get()方法用来获取私有属性
	private function __get($property_name)
	{
		echo "在直接获取私有属性值的时候，自动调用了这个__get()方法<br>";
		if(isset($this->$property_name))
		{
		return($this->$property_name);
		}else
		{
		return(NULL);
		}
	}
	//__set()方法用来设置私有属性
	private function __set($property_name, $value)
	{
		echo "在直接设置私有属性值的时候，自动调用了这个__set()方法为私有属性赋值<br>";
		$this->$property_name = $value;
	}

	//__isset()方法
	private function __isset($nm)
	{
	    echo "isset()函数测定私有成员时，自动调用<br>";
	    return isset($this->$nm);
	}
	//__unset()方法
	private function __unset($nm)
	{
	    echo "当在类外部使用unset()函数来删除私有成员时自动调用的<br>";
	    unset($this->$nm);
	}
}

$p1=new Person();
//直接为私有属性赋值的操作， 会自动调用__set()方法进行赋值
$p1->name="张三";
$p1->sex="男";
$p1->age=20;
//直接获取私有属性的值， 会自动调用__get()方法，返回成员属性的值
echo "姓名：".$p1->name."<br>";
echo "性别：".$p1->sex."<br>";
echo "年龄：".$p1->age."<br>";

//以上代码如果不加上__get()和__set()方法，程序就会出错，因为不能在类的外部操作私有成员，而上面的代码是通过自动调用__get()和__set()方法来帮助我们直接存取封装的私有成员的。如果条用的属性不是private，那么对象不会去调用预设的__get()和__set()方法。

$p1->name="this is a person name";
//在使用isset()函数测定私有成员时，自动调用__isset()方法帮我们完成，返回结果为true
echo var_dump(isset($p1->name))."<br>";
echo $p1->name."<br>";
//在使用unset()函数删除私有成员时，自动调用__unset()方法帮我们完成，删除name私有属性
unset($p1->name);
//已经被删除了， 所这行不会有输出
echo $p1->name;
?>
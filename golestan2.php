<?php


class students
{
    private $name;
    private $identical_num;
    private $field;
    private $entering_year;
    private $classes = array();
    private $marks = array();
    static private $counter = 0;
    private $number;

    public function __CONSTRUCT($name, $identical_num, $entering_year, $field)
    {
        $this->name = $name;
        $this->identical_num =$identical_num;
        $this->field = $field;
        $this->entering_year = $entering_year;
        self::$counter++;
        $this->number=self::$counter;
    }

    public function getID()
    {
        return $this->identical_num;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getEnteringYear()
    {
        return $this->entering_year;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setClass($class)
    {
        $this->classes[] = $class;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function setMark($class_id,$mark)
    {
        $this->marks[$class_id] = $mark;
    }

    public function getMark($class_id)
    {
        return $this->marks[$class_id] ?? null;
    }

    public function getMarks()
    {
        return $this->marks;
    }
}

class professors
{
    private $name;
    private $identical_num;
    private $field;
    private $classes= array();

    public function __CONSTRUCT($name, $identical_num, $field)
    {
        $this->name = $name;
        $this->identical_num =$identical_num;
        $this->field = $field;
    }

    public function getID()
    {
        return $this->identical_num;
    }

    public function getName(){
        return $this->name;
    }

    public function getField()
    {
        return $this->field;
    }

    public function setClass($class)
    {
        $this->classes[] = $class;
    }

    public function getClasses()
    {
        return $this->classes;
    }
}

class classes
{
    private $name;
    private $class_id;
    private $field;
    private $professor;
    private $students = array();

    public function __construct($name,$class_id,$field){
        $this->class_id=$class_id;
        $this->name=$name;
        $this->field = $field;
    }

    public function getID()
    {
        return $this->class_id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getField()
    {
        return $this->field;
    }

    public function studentExists($id)
    {
        foreach($this->students as $student){
            if($student->getID() == $id)return true;
        }
        return false;
    }

    public function addStudent($student)
    {
        $this->students[]=$student;
    }

    public function professorExists()
    {
        return isset($this->professor);
    }

    public function addProfessor($professor)
    {
        $this->professor=$professor;
    }

    public function getStudents()
    {
        return $this->students;
    }

    public function getProfessor()
    {
        return $this->professor ?? null;
    }
}


$students = array();
$professors = array();
$classes = array();

for (; ;) {
    $line = readline();
    if ($line === 'end') {
        die();
    }
    $line = preg_split('/\s+/', $line);
    if ($line[0] === 'register_student' or $line[0] === 'register_professor') {
        if (!isset($professors[$line[2]]) AND !isset($students[$line[2]])) {
            if($line[0] === 'register_student'){
                $students[$line[2]]=new students($line[1], $line[2],$line[3],$line[4]);
            }else{
                $professors[$line[2]]= new professors($line[1],$line[2],$line[3]);
            }
            echo 'welcome to golestan';
        }else{
            echo'this identical number previously registered';
        }
    }elseif($line[0] === 'make_class'){
        if(!isset($classes[$line[2]])){
            $classes[$line[2]] = new classes($line[1],$line[2],$line[3]);
            echo 'class added successfully';
        }else{
            echo 'this class id previously used';
        }
    }elseif($line[0] === 'add_student'){
        if(!isset($students[$line[1]])) {
            echo 'invalid student';
        }
        elseif(!isset($classes[$line[2]])){
            echo 'invalid class';
        }
        elseif($classes[$line[2]]->getField() !== $students[$line[1]]->getField()){
            echo 'student field is not match';
        }elseif($classes[$line[2]]->studentExists($line[1])){
            echo 'student is already registered';
        }else{
            $classes[$line[2]]->addStudent($students[$line[1]]);
            $students[$line[1]]->setClass($classes[$line[2]]);
            echo 'student added successfully to the class';
        }
    }elseif($line[0]==='add_professor'){
        if(!isset($professors[$line[1]])) {
            echo 'invalid professor';
        }
        elseif(!isset($classes[$line[2]])){
            echo 'invalid class';
        }
        elseif($classes[$line[2]]->getField() !== $professors[$line[1]]->getField()){
            echo 'professor field is not match';
        }elseif($classes[$line[2]]->professorExists()){
            echo 'this class has a professor';
        }else{
            $classes[$line[2]]->addProfessor($professors[$line[1]]);
            $professors[$line[1]]->setClass($classes[$line[2]]);
            echo 'professor added successfully to the class';
        }
    }elseif($line[0]==='student_status'){
        if(!isset($students[$line[1]])){
            echo 'invalid student';
        }else{
            $result=$students[$line[1]]->getname().' '.$students[$line[1]]->getEnteringYear().' '.$students[$line[1]]->getField();
            foreach($students[$line[1]]->getClasses() as $class){
                $result .=' '.$class->getName();
            }
            echo $result;
        }
    }elseif($line[0]==='professor_status'){
        if(!isset($professors[$line[1]])){
            echo 'invalid professor';
        }else{
            $result=$professors[$line[1]]->getname().' '.$professors[$line[1]]->getField();
            foreach($professors[$line[1]]->getClasses() as $class){
                $result .=' '.$class->getName();
            }
            echo $result;
        }
    }elseif($line[0]==='class_status'){
        if(!isset($classes[$line[1]])){
            echo 'invalid class';
        }else{
            $professor=$classes[$line[1]]->getProfessor();
            $result = ($professor==null) ? 'None' : $professor->getName();
            foreach($classes[$line[1]]->getStudents() as $student){
                $result .= ' ' . $student->getName();
            }
            echo $result;
        }
    }elseif($line[0]==='set_final_mark'){
        if(!isset($professors[$line[1]])){
            echo 'invalid professor';
        }elseif(!isset($students[$line[2]])){
            echo 'invalid student';
        }elseif(!isset($classes[$line[3]])){
            echo 'invalid class';
        }elseif($classes[$line[3]]->getProfessor()==null){
            echo 'professor class is not match';
        }elseif($classes[$line[3]]->getProfessor()->getID()!=$line[1]){
            echo 'professor class is not match';
        }elseif(!$classes[$line[3]]->studentExists($line[2])){
            echo 'student did not registered';
        }else{
            $students[$line[2]]->setMark($line[3],$line[4]);
            echo 'student final mark added or changed';
        }
    }elseif($line[0]==='mark_student'){
        if(!isset($students[$line[1]])){
            echo 'invalid student';
        }elseif(!isset($classes[$line[2]])){
            echo 'invalid class';
        }elseif(!$classes[$line[2]]->studentExists($line[1])){
            echo 'student did not registered';
        }else{
            echo $students[$line[1]]->getMark($line[2]) ?? 'None';
        }
    }elseif($line[0]==='mark_list'){
        if(!isset($classes[$line[1]])){
            echo 'invalid class';
        }elseif(empty($classes[$line[1]]->getProfessor())){
            echo 'no professor';
        }elseif(empty($classes[$line[1]]->getStudents())){
            echo 'no student';
        }else{
            foreach($classes[$line[1]]->getStudents() as $student){
                $mark = $student->getMark($line[1]) ?? 'None';
                echo $mark.' ';
            }
        }
    }elseif($line[0]==='average_mark_professor'){
        if(!isset($professors[$line[1]])){
            echo 'invalid professor';
        }else{
            $marks = array();
            foreach($professors[$line[1]]->getClasses() as $class){
                foreach($class->getStudents() as $student){
                    $mark = $student->getMark($class->getID());
                    if($mark != null){
                        $marks[] = $mark;
                    }
                }
            }
            if(empty($marks)){
                echo 'None';
            }else{
                echo array_sum($marks)/count($marks);
            }
        }
    }elseif($line[0]==='average_mark_student'){
        if(!isset($students[$line[1]])){
            echo 'invalid student';
        }else{
            if(empty($students[$line[1]]->getMarks())){
                echo 'None';
            }else{
                $marks=$students[$line[1]]->getMarks();
                echo array_sum($marks)/count($marks);
            }
        }
    }elseif($line[0]==='top_student'){
        $averages=array();
        foreach($students as $student){
            if($student->getEnteringYear()==$line[2] AND $student->getField()==$line[1]){
                $marks=$student->getMarks();
                if(!empty($marks)){
                    $averages[$student->getID()]=array_sum($marks)/count($marks);
                }
            }
        }
        if(empty($averages)){
            echo 'None';
        }else{
            $max=max($averages);
            $arr=array_filter($averages,function($average) use($max){
                return $max==$average;
            });
            $n=$students[array_search($max,$arr)]->getNumber();
            $ID=0;
            foreach($arr as $id=>$avg){
                if($students[$id]->getNumber()<=$n){
                    $ID=$id;
                }
            }
            echo $students[$ID]->getName();
        }
    }elseif($line[0]==='top_mark'){
        if(!isset($classes[$line[1]])){
            echo 'invalid class';
        }else{
            $marks=array();
            foreach($classes[$line[1]]->getStudents() as $student){
                $mark = $student->getMark($line[1]);
                if($mark!=null){
                    $marks[]=$mark;
                }
            }
            if(empty($marks)){
                echo 'None';
            }else{
                echo max($marks);
            }
        }
    }
}
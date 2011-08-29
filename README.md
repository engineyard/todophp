# Converting the Demo App to Orchestra
I was asked by Nic Williams to convert the Engine Yard sample application to PHP for the purpose of deploying in on Orchestra. Since the Orchestra guys are affiliated with [Lithium](http://lithify.me/), I chose to use it.

# Setup

## XAMPP

The first step was to go get a self contained PHP / Apache package, because I already have some special configs with my local OSX Lion bits. I chose to use [XAMPP](http://www.apachefriends.org/en/xampp.html). I opened the XAMPP control panel, fired up Apache and MySQL and proceeded.

## Sequel Pro

Next up, I went off to [http://www.sequelpro.com/] and downloaded my favorite GUI SQL client. Hey, I like GUI's, I'm sorry. And no, we won't be using SQLite, although that is perfectly do-able and supported in XAMPP.

## Lithium

As the son of a psychiatrist, this element has special meaning, but in this case we are talking about the PHP Framework.

1. Downloaded Lithium from [SourceForge](http://sourceforge.net/projects/li3/)
2. Put the contents in my /home/mreider/Applications/XAMPP/htdocs/todophp

From this point on, I will be working in the Lithium directory, which on my laptop is <code>/home/mreider/Applications/XAMPP/htdocs/todophp</code>. I will refer to this directory as <code>(todophp)/</code>.

The Lithium docs recommend that we turn on better debugging by modifying <code>(todophp)/app/config/bootstrap/bootstrap.php</code> as follows:

     ini_set("display_errors", 1);

## Create a Database

Fire up the Sequel Pro GUI. Login to <code>localhost</code> using <code>root</code> and no password. and create a new database with nothing in it. We will call it <code>todo</code>. 

Next we need to setup the database connection in <code>(todophp)/app/config/bootstrap/connections</code>

    Connections::add('default', array(
    'type' => 'database',
    'adapter' => 'MySql',
    'host' => 'localhost',
    'login' => 'root',
    'password' => '',
    'database' => 'todo',
    'encoding' => 'UTF-8'
     ));

## Testing the setup

At this point you can browse <code>http://localhost/todophp/</code> and run some tests to make sure things are good to go.

# Database Schema

First thing I did was to clone our demo app from the [Engineyard todo git repository](http://github.com/engineyard/todo) and start copying the models over. There are two models in the demo app: **task** and **list**.

I went into the Ruby schema file in db/migrate/schema.rb to see what tables I had to make. **Lithium has no CLI** so there is no way to create database tables without hitting the MySQL database directly.

Here are the tables I created.

    CREATE TABLE 'tasks' (
    'id' int(11) unsigned NOT NULL AUTO_INCREMENT,
    'name' varchar(255) DEFAULT NULL,
    'done' tinyint(1) DEFAULT NULL,
    'updated_at' datetime DEFAULT NULL,
    PRIMARY KEY ('id')
    )

	CREATE TABLE 'lists' (
	  'id' int(11) unsigned NOT NULL AUTO_INCREMENT,
	  'name' varchar(255) DEFAULT NULL,
	  PRIMARY KEY ('id')
	)

# From Ruby to PHP

## Models

### Ruby Models

First let's look at the Ruby models, and then convert them to PHP Lithium.

/app/models/list.rb...

    class List < ActiveRecord::Base

	  validates :name, :presence => true
	  validates_uniqueness_of :name, :on => :create, :message => "must be unique"

	  has_many :tasks , :dependent => :destroy

	  def done_tasks
	     tasks.where(:done => true).order("updated_at DESC")
	  end

	end
	
/app/models/task.rb...

	class Task < ActiveRecord::Base
    
	    belongs_to :list, :class_name => "List", :foreign_key => "list_id"
    
	    validates :name, :presence => true


	end

	
### PHP Models

In Lithium, the same code looks like this. The Lithium team seems to like plurals, and there is no magic for pluralization and such, so it matters very little. Also, there is no :dependent symbol, so we have to cascade deletes by hand later.

We added a validation rule for name.

(todophp)/app/models/Tasks.php

    namespace app\models;

	class Tasks extends \lithium\data\Model {
		
		public $validates = array(
		    'name' => 'please enter a name'
		);

	}
	
(todophp)/app/models/Lists.php
	
	namespace app\models;

	class Lists extends \lithium\data\Model {
		
		public $validates = array(
		    'name' => array(
				array('notEmpty', 'name' => 'Name cannot be empty'),
				array('unique', 'message' => 'Name has to be unique.')
		);
		
		protected $hasMany = array('Tasks');
    }

In the Ruby version of this List Model, there was a validation rule for unique names. To accomplish the same functionality, We can add this validation rule to the Validator class by using the <code>Validator::add</code> method. I put this in a new file.

(todophp)/app/config/bootstrap/validator.php

    use \lithium\util\Validator;

	Validator::add('unique', function (&$value, $format = null, array $options = array()) {
		$conditions = array(
			'_id' => array('$ne' => $options['values']['_id']),
			$options['field'] => $value,
		);
		$count = $options['model']::count(compact('conditions'));

		return $count == 0;
	});
	
To add this to the bootstrap list, I added the file reference to <code>(todophp)/app/config/bootstrap.php</code>.

    // validator rules
	require __DIR__ . '/bootstrap/validator.php';
	
## Controllers

### Ruby Controllers

Here are the List and Task controllers in Ruby

/app/controllers/lists_controller.rb...

    class ListsController < ApplicationController

	  def create
	    @list = List.new(params[:list])
	    if @list.save
	        flash[:notice] = "Your list was created"
	    else
	        flash[:alert] = "There was an error creating your list."
	    end
	    redirect_to(tasks_url(:list => @list.id))
	  end

	  def destroy
	    @list = List.find(params[:id])
	    @list.destroy

	    respond_to do |format|
	      format.html { redirect_to(tasks_url) }
	    end
	  end
	end
	
/app/controllers/tasks_controller.rb...

class TasksController < ApplicationController

	def index
	    @todo   = Task.where(:done => false)
	    @task   = Task.new
	    @lists  = List.all
	    @list   = List.new
    
	    respond_to do |format|
	      format.html
	    end
	  end


	  def create
	    @task = Task.new(params[:task])
	    if @task.save
	        flash[:notice] = "Your task was created."
	    else
	        flash[:alert] = "There was an error creating your task."
	    end
	    redirect_to(tasks_url(:list => params[:task][:list_id]))
	  end
  

	  def update
	    @task = Task.find(params[:id])

	    respond_to do |format|
	      if @task.update_attributes(params[:task])
	        format.html { redirect_to( tasks_url(:list => @task.list.id), :notice => 'Task was successfully updated.') }
	      else
	        format.html { render :action => "edit" }
	      end
	    end
	  end


	  def destroy
	    @task = Task.find(params[:id])
	    @task.destroy

	    respond_to do |format|
	      format.html { redirect_to(tasks_url(:list => @task.list_id)) }
	    end
	  end
  
 
	end

### PHP Controllers





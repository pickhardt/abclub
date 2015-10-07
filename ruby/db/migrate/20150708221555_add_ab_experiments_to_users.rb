class AddABExperimentsToUsers < ActiveRecord::Migration
  def change
    add_column :ab_experiments, :hidden, :boolean, :default => false
  end
end

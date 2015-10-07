Dir[File.dirname(__FILE__) + '/helpers/*.rb'].each do |file|
  require file
end

Dir[File.dirname(__FILE__) + '/abclub/*.rb'].each do |file|
  require file
end

module ABClub
  extend ModuleVars

  create_module_var(:env)
  create_module_var(:cookie_getter, lambda { |*args| RailsCookies.get(*args) })
  create_module_var(:cookie_setter, lambda { |*args| RailsCookies.set(*args) })
  create_module_var(:cookie_duration, 365)
  create_module_var(:current_user, lambda { |*args| current_user })
  create_module_var(:test_method, lambda { |*args| return 3 })

  def self.register_environment(env)
    self.env = env
  end
end

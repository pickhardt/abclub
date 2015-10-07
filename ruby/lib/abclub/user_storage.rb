require(File.expand_path('storage', File.dirname(__FILE__)))

module ABClub
  class UserStorage < Storage
    def self.get(experiment)
      current_user.experiments[experiment.name]
    end

    def self.set(experiment, variation_name)
      current_user.experiments[experiment.name] = variation_name
    end
  end
end

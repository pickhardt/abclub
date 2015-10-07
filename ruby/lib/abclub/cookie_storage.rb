require(File.expand_path('storage', File.dirname(__FILE__)))

module ABClub
  class CookieStorage < Storage
    def self.get(experiment)
      ABClub.cookie_getter.call(cookie_name(experiment))
    end

    def self.set(experiment, variation_name)
      duration = ABClub.cookie_duration
      ABClub.cookie_setter.call(cookie_name(experiment), variation_name, duration)
    end

    private
    def self.cookie_name(experiment)
      "ab_#{ experiment.name }"
    end
  end
end

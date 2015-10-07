module ABClub
  class RailsCookies
    def self.get(name)
      ABClub.env.cookie[name]
    end

    def self.set(name, val, duration)
      ABClub.env.cookie[name] = {
          value: val,
          expires: duration,
      }
    end
  end
end

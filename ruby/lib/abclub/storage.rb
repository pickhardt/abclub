module ABClub
  class Storage
    def self.get
      raise "Abstract class Storage's method get should get implemented elsewhere."
    end

    def self.set
      raise "Abstract class Storage's method set should get implemented elsewhere."
    end
  end
end

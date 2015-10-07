module ABClub
  class Variation
    attr_accessor :name
    attr_accessor :probability
    attr_accessor :metadata

    def initialize(name, probability, metadata = {})
      if name.is_a?(Hash) && name['name']
        name = name['name']
      end

      if name.is_a?(Hash) && name['probability']
        probability = name['probability']
      end

      if name.is_a?(Hash) && name['metadata']
        metadata = name['metadata']
      end

      self.name = name
      self.probability = probability

      # Metadata can be used to store data alongside the variation. Especially if the data will be used in more than one
      # place, it's more DRY to store the data with the variation. An example might be testing the denominator for an
      # invite your friends progress bar, where you store 3 for "You've shared # out of 3", 5 for "out of 5" or
      # 10 for "out of 10"
      self.metadata = metadata
    end

    def bucket

    end
  end
end

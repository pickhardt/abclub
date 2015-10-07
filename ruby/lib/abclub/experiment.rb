module ABClub
  extend ModuleVars

  create_module_var("experiments", [])

  def self.register_experiment(name, possible_variations, options = {})
    self.experiments << Experiment.new(name, possible_variations, options)
  end

  def self.experiment(name)
    self.experiments.select { |experiment| experiment.name == name }.first
  end

  class Experiment
    attr_accessor :name
    attr_accessor :possible_variations
    attr_accessor :options
    attr_accessor :storage

    def initialize(name, passed_variations, options = {})
      self.name = name

      if passed_variations.kind_of?(Array)
        self.possible_variations = passed_variations.map { |name| Variation.new(name, Rational(1, passed_variations.length)) }
      elsif passed_variations.kind_of?(Hash)
        self.possible_variations = []
        passed_variations.each do |key, data|
          probability = data.kind_of?(Hash) ? data[:probability] : data
          self.possible_variations << Variation.new(key, probability, data)
        end
      else
        raise ArgumentError("passed_variations parameter must be Array or Hash")
      end

      self.should_add_to_1!

      self.options = Experiment.default_options.merge(options)

      self.storage = self.options[:storage]
    end

    def autoassign?
      self.options[:autoassign]
    end

    def assign
      bucketed_in = self.variation(true)
      if !bucketed_in
        bucketed_in = self.random_variation
        self.variation = bucketed_in
      end
      return self
    end

    def random_variation
      sum = 0.0
      random_number = rand
      self.possible_variations.each do |variation|
        sum += variation.probability
        if random_number <= sum
          return variation
        end
      end

      raise "if you get here, something is wrong, perhaps the variation probabilities don't add up to 1"
    end

    def should_add_to_1!
      sum = 0.0
      self.possible_variations.each do |variation|
        sum += variation.probability
      end

      if sum != 1
        raise "variation probabilities must add up to 1"
      end
    end

    def variation(skip_auto_assign = false)
      assigned_in = self.storage.get(self)
      if assigned_in.nil? && self.autoassign? && !skip_auto_assign
        self.assign
      end
    end

    def variation=(value)
      self.storage.set(self, value)
    end

    private
      def self.default_options
        {
            autoassign: true,
            storage: CookieStorage,
        }
      end
  end
end

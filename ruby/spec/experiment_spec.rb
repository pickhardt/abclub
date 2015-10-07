require 'spec_helper'

describe ABClub::Experiment do
  it 'randomizes correctly with an even distribution' do
    experiment = ABClub::Experiment.new('stooges', ['larry', 'curly', 'moe'])
    counts = {
        'larry' => 0,
        'curly' => 0,
        'moe' => 0,
    }

    n = 100_000
    for i in (0..n) do
      selected_variation = experiment.random_variation
      counts[selected_variation.name] += 1
    end

    expect(counts['larry']).to be_within(n / 100).of(n / 3)
    expect(counts['curly']).to be_within(n / 100).of(n / 3)
    expect(counts['moe']).to be_within(n / 100).of(n / 3)
  end

  it 'randomizes correctly with an uneven distribution' do
    experiment = ABClub::Experiment.new('stooges', {'control' => 0.8, 'uncommon' => 0.18, 'rare' => 0.02})
    counts = {
        'control' => 0,
        'uncommon' => 0,
        'rare' => 0,
    }

    n = 1_000_000
    for i in (0..n) do
      selected_variation = experiment.random_variation
      counts[selected_variation.name] += 1
    end

    expect(counts['control']).to be_within(n / 100).of(n * 0.80)
    expect(counts['uncommon']).to be_within(n / 100).of(n * 0.18)
    expect(counts['rare']).to be_within(n / 100).of(n * 0.02)
  end
end

require_relative '../spec_helper'
require_relative '../../lib/word_finder'

describe WordFinder do
  describe '#find_words' do
    let(:fields) { [ %w[ c o ], %w[ o l ] ] }
    let(:lengths) { [ 4 ] }

    it 'returns two words when dict is used' do
      WordFinder.new(fields, lengths, true).find_words.sort.must_equal %w[ cool loco ]
    end

    it 'returns many words when dict is used' do
      WordFinder.new(fields, lengths, false).find_words.sort.must_equal %w[ cloo colo cool lcoo loco looc oclo ocol olco oloc oocl oolc ]
    end
  end
end

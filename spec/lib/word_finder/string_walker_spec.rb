require_relative '../../spec_helper'
require_relative '../../../lib/word_finder/string_walker'

describe StringWalker do
  let(:scanner) { StringWalker.new matrix }

  describe '1x1' do
    let(:matrix) { Matrix.new [ %w[ a ] ] }

    it 'returns one word for length 1' do
      scanner.scan(1, 0, 0).must_equal Set[*%w[ a ]]
    end

    it 'raises an error on out of bounds' do
      err = ->{ scanner.scan(1, 0, 1) }.must_raise RuntimeError
      err.message.must_match /invalid pos/
    end
  end

  describe '1x2' do
    let(:matrix) { Matrix.new [ %w[ a ], %w[ b ] ] }

    it 'returns one word for length 1' do
      scanner.scan(1, 0, 0).must_equal Set[*%w[ a ]]
    end

    it 'returns one word for length 1 at (0,1)' do
      scanner.scan(1, 0, 1).must_equal Set[*%w[ b ]]
    end

    it 'returns one word for length 2' do
      scanner.scan(2, 0, 0).must_equal Set[*%w[ ab ]]
    end

    it 'returns one word for length 2 at (0,1)' do
      scanner.scan(2, 0, 1).must_equal Set[*%w[ ba ]]
    end
  end

  describe '2x2' do
    let(:matrix) { Matrix.new [ %w[ a b ], %w[ c d ] ] }

    it 'returns one word for length 1' do
      scanner.scan(1, 0, 0).must_equal Set[*%w[ a ]]
    end

    it 'returns three words for length 2' do
      scanner.scan(2, 0, 0).must_equal Set[*%w[ ab ac ad ]]
    end

    it 'returns three words for length 2 at (0,1)' do
      scanner.scan(2, 0, 1).must_equal Set[*%w[ ca cb cd ]]
    end

    it 'returns six words for length 3' do
      scanner.scan(3, 0, 0).must_equal Set[*%w[ abc abd acb acd adb adc ]]
    end

    it 'returns six words for length 4' do
      scanner.scan(4, 0, 0).must_equal Set[*%w[ acbd acdb abcd abdc adcb adbc ]]
    end
  end
end
